<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR;

trait InputValidation
{
    /**
     * Validates input data is not empty or null
     *
     * @param int|string|null $data
     * @return string
     */
    public function validateData(int|string $data = null): string
    {
        if (strlen(strval($data)) > 0) return strval($data);

        throw new LuxiQRException("Invalid or empty data provided");
    }

    /**
     * Validates error correction level or defaults to medium if null
     *
     * @param string|null $eccLevel
     * @return string
     */
    public function validateECCLevel(string $eccLevel = null): string
    {
        if (strlen(trim(strval($eccLevel))) == 0) return self::EC_MEDIUM;

        return match (strtoupper(strval($eccLevel))[0]) {
            self::EC_LOW => self::EC_LOW,
            self::EC_QUARTILE => self::EC_QUARTILE,
            self::EC_MEDIUM => self::EC_MEDIUM,
            self::EC_HIGH => self::EC_HIGH,
            default => throw new LuxiQRException("Invalid ECC level: $eccLevel")
        };
    }

    /**
     * Validates encoding mode or detects it if null
     *
     * @param string|null $mode
     * @return string
     */
    public function validateMode(string $mode = null): string
    {
        return match (strtolower(strval($mode))) {
            "" => $this->detectEncodingMode(),
            self::NUMERIC, "numeric" => self::NUMERIC,
            self::ALPHANUMERIC, "alphanumeric" => self::ALPHANUMERIC,
            self::BYTE, "byte" => self::BYTE,
            self::KANJI, "kanji" => self::KANJI,
            default => throw new LuxiQRException("Invalid mode: $mode")
        };
    }

    /**
     * Validates the version number or detects it if null
     *
     * @param int|null $version
     * @return int
     */
    public function validateVersion(int $version = null): int
    {
        if (is_null($version)) return $this->detectVersion();

        if (1 > $version || 40 < $version) throw new LuxiQRException("Invalid version: $version");

        return $version;
    }
}