<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR;

trait EncodeTrait
{
    /**
     * Encodes input data
     *
     * @param string|null $data
     * @param string|null $mode
     * @param string|null $eccLevel
     * @return string
     */
    protected function encode(): string
    {
        $encodedData = match ($this->mode) {
            self::NUMERIC => $this->encodeNumeric($this->data),
            self::ALPHANUMERIC => $this->encodeAlphanumeric($this->data),
            self::KANJI => $this->encodeKanji($this->data),
            default => $this->encodeByte($this->data)
        };

        $countIndicator = str_pad(
            string: decbin(strlen($this->data)),
            length: $this->detectCountIndicatorSize(),
            pad_string: LuxiQR::PAD_DATA,
            pad_type: STR_PAD_LEFT
        );

        $encodedData = $this->mode . $countIndicator . $encodedData;

        // padding
        return str_pad( // payload padding
            string: str_pad( // terminator padding
                string: $encodedData,
                length: intval(ceil(strlen($encodedData) / 8) * 8),
                pad_string: LuxiQR::PAD_DATA
            ),
            length: LuxiQR::BYTE_COUNT_TABLE[$this->version][$this->eccLevel]["total"] * 8,
            pad_string: LuxiQR::PAD_ENCODED
        );
    }

    /**
     * Detects the most appropriate encoding mode
     *
     * @param string|null $data
     * @return string
     */
    protected function detectEncodingMode(): string
    {
        if (preg_match(pattern: self::REGEX_NUMERIC, subject: $this->data))
            return self::NUMERIC;

        if (preg_match(pattern: self::REGEX_ALPHANUMERIC, subject: $this->data))
            return self::ALPHANUMERIC;

        if ($this->isKanjiOnly($this->data)) {
            return self::KANJI;
        }

        return self::BYTE;
    }

    /**
     * Encodes a numeric string
     *
     * @param string $data
     * @return string
     */

    private function encodeNumeric(string $data): string
    {
        $encoded = "";

        if (!preg_match(pattern: self::REGEX_NUMERIC, subject: $data)) {
            throw new LuxiQRException("Cannot use numeric encoding on non-numeric data: $data");
        }

        for ($i = 0; $i < strlen(string: $data); $i += 3) {
            $decimal = intval(value: substr(string: $data, offset: $i, length: 3));

            if (99 < $decimal) {
                $paddingLength = 10;
            } elseif (9 < $decimal) {
                $paddingLength = 7;
            } else {
                $paddingLength = 4;
            }

            $binary = str_pad(
                string: decbin(num: $decimal),
                length: $paddingLength,
                pad_string: LuxiQR::PAD_DATA,
                pad_type: STR_PAD_LEFT
            );

            $encoded .= $binary;
        }

        return $encoded;
    }

    /**
     * Encodes an alphanumeric string
     *
     * @param string $data
     * @return string
     */
    private function encodeAlphanumeric(string $data): string
    {
        $characterSet = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ $%*+-./:";
        $encoded = "";

        if (!preg_match(pattern: self::REGEX_ALPHANUMERIC, subject: $data)) {
            throw new LuxiQRException("Cannot use alphanumeric encoding on non-alphanumeric data: $data");
        }

        for ($i = 0; $i < strlen(string: $data); $i += 2) {
            $first = substr(string: $data, offset: $i, length: 1);
            $second = substr(string: $data, offset: $i + 1, length: 1);

            if ($second) {
                $decimal = strpos(haystack: $characterSet, needle: $first) * 45 + strpos(haystack: $characterSet, needle: $second);
                $binary = str_pad(
                    string: decbin(num: $decimal),
                    length: 11,
                    pad_string: LuxiQR::PAD_DATA,
                    pad_type: STR_PAD_LEFT
                );
            } else {
                $decimal = strpos(haystack: $characterSet, needle: $first);
                $binary = str_pad(
                    string: decbin(num: $decimal),
                    length: 6,
                    pad_string: LuxiQR::PAD_DATA,
                    pad_type: STR_PAD_LEFT
                );
            }
            $encoded .= $binary;
        }

        return $encoded;
    }

    /**
     * Encodes a Shift JIS string
     *
     * @param string $data
     * @return string
     * @throws LuxiQRException
     */
    private function encodeKanji(string $data): string
    {
        $encoded = "";

        if (!$this->isKanjiOnly($data)) {
            throw new LuxiQRException("Cannot use Kanji encoding on non-Shift JIS data: $data");
        }

        $data = mb_convert_encoding($data, to_encoding: "SHIFT-JIS", from_encoding: mb_detect_encoding($data));

        $bytes = unpack('H*', $data)[1];

        for ($i = 0; $i < strlen(string: $bytes); $i += 4) {
            $doubleByte = hexdec(hex_string: substr(string: $bytes, offset: $i, length: 4));
            $character = (0x9FFC >= $doubleByte) ? $doubleByte - 0x8140 : $doubleByte - 0xC140;
            $byte1 = $character >> 8 & 0x00FF;
            $byte2 = $character & 0x00FF;
            $doubleByte = ($byte1 * 0xc0) + $byte2;
            $encoded .= str_pad(
                string: decbin($doubleByte),
                length: 13,
                pad_string: LuxiQR::PAD_DATA,
                pad_type: STR_PAD_LEFT
            );
        }

        return $encoded;
    }

    /**
     * Encodes a string using ISO-8859-1 encoding with UTF-8 as a fallback encoding
     *
     * @param string $data
     * @return string
     */
    private function encodeByte(string $data): string
    {
        $encoded = "";

        // ISO-8859-1 is the primary standard, but some readers allow UTF-8, so try ISO first with a UTF-8 fallback
        $encoding = ($this->isISO88591Only($data)) ? "ISO-8859-1" : "UTF-8";

        $data = mb_convert_encoding(string: $data, to_encoding: $encoding, from_encoding: mb_detect_encoding($data));

        $bytes = unpack(format: 'C*', string: $data);

        foreach ($bytes as $byte) {
            $encoded .= str_pad(
                string: decbin(num: $byte),
                length: 8,
                pad_string: LuxiQR::PAD_DATA,
                pad_type: STR_PAD_LEFT
            );
        }

        return $encoded;
    }

    /**
     * Checks if a string contains exclusively SHIFT-JIS characters
     *
     * @param string $data
     * @return bool
     */
    private function isKanjiOnly(string $data): bool
    {
        // Adapted from BaconQrCode
        // Reference: https://github.com/Bacon/BaconQrCode
        $bytes = @iconv('utf-8', 'SHIFT-JIS', $data);

        if (false === $bytes) return false;

        if (0 !== strlen($bytes) % 2) return false;

        for ($i = 0; $i < strlen($bytes); $i += 2) {
            $byte = ord($bytes[$i]) & 0xff;

            if (($byte < 0x81 || $byte > 0x9f) && $byte < 0xe0 || $byte > 0xeb) return false;
        }

        return true;
    }

    /**
     * Checks if a string contains exclusively ISO-8859-1 characters
     *
     * @param string $data
     * @return bool
     */
    private function isISO88591Only(string $data): bool
    {
        // TODO: I think I should change the data encoding and convert to bytes like isKanjiOnly()
        for ($i = 0; $i < strlen($data); $i++) {
            $byte = ord($data[$i]);

            if ($byte < 0x00 || $byte > 0xFF) return false;
        }

        return true;
    }
}