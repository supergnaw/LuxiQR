<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR;

trait InputValidation
{
    protected function validateData(int|string $data = null): string
    {
        if (strlen(strval($data)) > 0) return strval($data);

        throw new LuxiQRException("Invalid or empty data provided");
    }

    protected function validateECCLevel(string $eccLevel = null): string
    {
        return match (strtoupper(strval($eccLevel))) {
            self::EC_LOW => self::EC_LOW,
            self::EC_QUARTILE => self::EC_QUARTILE,
            "", self::EC_MEDIUM => self::EC_MEDIUM,
            self::EC_HIGH => self::EC_HIGH,
            default => throw new LuxiQRException("Invalid ECC level: $eccLevel")
        };
    }

    protected function validateMode(string $mode = null): string
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

    protected function validateVersion(int $version = null): int
    {
        if (is_null($version)) return $this->detectVersion();

        if (1 > $version || 40 < $version) throw new LuxiQRException("Invalid version: $version");

        return $version;
    }
}