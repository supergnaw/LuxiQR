<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR;

trait EncodeTrait
{
    /**
     * Encodes input data
     *
     * @return void
     */
    protected function encodeData(): void
    {
        $this->characterCount = 0;

        $encodedData = match ($this->mode) {
            self::NUMERIC => $this->encodeNumeric($this->data),
            self::ALPHANUMERIC => $this->encodeAlphanumeric($this->data),
            self::KANJI => $this->encodeKanji($this->data),
            self::BYTE => $this->encodeByte($this->data),
            default => throw new LuxiQRException("Encoding mode $this->mode not supported"),
        };

        $countIndicator = str_pad(
            string: decbin($this->characterCount),
            length: $this->detectCountIndicatorSize(),
            pad_string: LuxiQR::PAD_DATA,
            pad_type: STR_PAD_LEFT
        );

        $encodedData = $this->mode . $countIndicator . $encodedData;

        // padding
        $this->encodedData = str_pad( // terminator padding
            string: str_pad( // data padding
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
     * @return string
     */
    protected function detectEncodingMode(): string
    {
        if (preg_match(pattern: self::REGEX_NUMERIC, subject: $this->data))
            return self::NUMERIC;

        if (preg_match(pattern: self::REGEX_ALPHANUMERIC, subject: $this->data))
            return self::ALPHANUMERIC;

        if ($this->fitsCharacterSet($this->data, "SHIFT-JIS")) {
            return self::KANJI;
        }

        return self::BYTE;
    }

    /**
     * Detects the number of bits for the count indicator
     *
     * @return int
     */
    private function detectCountIndicatorSize(): int
    {
        // using black magic to detect the proper count indicator size
        return match ($this->version <= 9 ? 9 : ($this->version <= 26 ? 26 : 40)) {
            9 => [self::NUMERIC => 10, self::ALPHANUMERIC => 9, self::BYTE => 8, self::KANJI => 8][$this->mode],
            26 => [self::NUMERIC => 12, self::ALPHANUMERIC => 11, self::BYTE => 16, self::KANJI => 10][$this->mode],
            40 => [self::NUMERIC => 14, self::ALPHANUMERIC => 13, self::BYTE => 16, self::KANJI => 12][$this->mode],
        };
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
            $this->characterCount++;

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
                $this->characterCount += 2;

                $decimal = strpos(haystack: $characterSet, needle: $first) * 45 + strpos(haystack: $characterSet, needle: $second);
                $binary = str_pad(
                    string: decbin(num: $decimal),
                    length: 11,
                    pad_string: LuxiQR::PAD_DATA,
                    pad_type: STR_PAD_LEFT
                );
            } else {
                $this->characterCount += 1;

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

        if (!$this->fitsCharacterSet($data, characterSet:  "SHIFT-JIS")) {
            throw new LuxiQRException("Cannot use Kanji encoding on non-Shift JIS data: $data");
        }

        $data = mb_convert_encoding($data, to_encoding: "SHIFT-JIS", from_encoding: mb_detect_encoding($data));

        $bytes = unpack('H*', $data)[1];

        for ($i = 0; $i < strlen(string: $bytes); $i += 4) {
            $this->characterCount++;

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
        $encoding = ($this->fitsCharacterSet($data, "ISO-8859-1")) ? "ISO-8859-1" : "UTF-8";

        $data = mb_convert_encoding(string: $data, to_encoding: $encoding, from_encoding: mb_detect_encoding($data));

        $bytes = unpack(format: 'C*', string: $data);

        foreach ($bytes as $byte) {
            $this->characterCount++;

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
     * Checks to see if a data string contains only characters within a given character encoding
     *
     * @param string $data
     * @param string $characterSet
     * @return bool
     */
    private function fitsCharacterSet(string $data, string $characterSet): bool
    {
        $originalEncoding = mb_detect_encoding($data);
        $converted = @iconv($originalEncoding, "$characterSet//IGNORE", $data);
        $reconverted = @iconv($characterSet, $originalEncoding, $converted);

        return $data === $reconverted;
    }
}