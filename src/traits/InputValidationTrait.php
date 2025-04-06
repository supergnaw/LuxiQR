<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR\traits;

use supergnaw\LuxiQR\constants\ErrorCorrection;
use supergnaw\LuxiQR\constants\Modes;
use supergnaw\LuxiQR\exception\LuxiQRException;

trait InputValidationTrait
{
    /**
     * Validates input data is not empty or null
     *
     * @param int|string|null $data
     * @return string
     */
    protected function validateData(int|string $data = null): string
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
    protected function validateECCLevel(string $eccLevel = null): string
    {
        if (strlen(trim(strval($eccLevel))) == 0) return ErrorCorrection::MEDIUM;

        return match (strtoupper(strval($eccLevel))[0]) {
            ErrorCorrection::LOW => ErrorCorrection::LOW,
            ErrorCorrection::QUARTILE => ErrorCorrection::QUARTILE,
            ErrorCorrection::MEDIUM => ErrorCorrection::MEDIUM,
            ErrorCorrection::HIGH => ErrorCorrection::HIGH,
            default => throw new LuxiQRException("Invalid ECC level: $eccLevel")
        };
    }

    /**
     * Validates encoding mode or detects it if null
     *
     * @param string|null $mode
     * @return string
     */
    protected function validateMode(string $mode = null): string
    {
        return match (strtolower(strval($mode))) {
            "" => $this->detectEncodingMode(),
            Modes::NUMERIC, "numeric" => Modes::NUMERIC,
            Modes::ALPHANUMERIC, "alphanumeric" => Modes::ALPHANUMERIC,
            Modes::BYTE, "byte" => Modes::BYTE,
            Modes::KANJI, "kanji" => Modes::KANJI,
            default => throw new LuxiQRException("Invalid mode: $mode")
        };
    }

    /**
     * Validates the version number or detects it if null
     *
     * @param int|null $version
     * @return int
     */
    protected function validateVersion(int $version = null): int
    {
        if (is_null($version)) return $this->detectVersion();

        if (1 > $version || 40 < $version) throw new LuxiQRException("Invalid version: $version");

        return $version;
    }
}