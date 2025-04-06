<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR\traits;

use supergnaw\LuxiQR\constants\CapacityTables;
use supergnaw\LuxiQR\constants\ErrorCorrection;
use supergnaw\LuxiQR\exception\LuxiQRException;
use supergnaw\LuxiQR\LuxiQR;

/**
 * https://www.thonky.com/qr-code-tutorial/format-version-tables
 */
trait VersionFormatTrait
{
    /**
     * Detects the version of QR Code to use based on data size, error correction leve, and encoding mode
     *
     * @param string|null $data
     * @param string|null $ecc
     * @param string|null $mode
     * @return int
     */
    protected function detectVersion(): int
    {
        for ($v = 1; $v <= 40; $v++) {
            if (strlen($this->data) <= CapacityTables::CHARACTER[$v][$this->eccLevel][$this->mode]) {
                return $v;
            }
        }

        throw new LuxiQRException("Payload byte count is too large: " . strlen($this->data));
    }

    /**
     * Adds the format string to the upper left, upper right, and lower left matrix corners
     *
     * @return void
     * @throws LuxiQRException
     */
    protected function addFormatString(): void
    {
        $formatString = $this->getFormatString();

        if (!$formatString) return;

        $formatPatternTopLeft = [
            [null, null, null, null, null, null, null, null, intval($formatString[14])],
            [null, null, null, null, null, null, null, null, intval($formatString[13])],
            [null, null, null, null, null, null, null, null, intval($formatString[12])],
            [null, null, null, null, null, null, null, null, intval($formatString[11])],
            [null, null, null, null, null, null, null, null, intval($formatString[10])],
            [null, null, null, null, null, null, null, null, intval($formatString[9])],
            [null, null, null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null, null, intval($formatString[8])],
            [
                intval($formatString[0]),
                intval($formatString[1]),
                intval($formatString[2]),
                intval($formatString[3]),
                intval($formatString[4]),
                intval($formatString[5]),
                null,
                intval($formatString[6]),
                intval($formatString[7])
            ],
        ];

        $formatPatternTopRight = [
            [
                intval($formatString[7]),
                intval($formatString[8]),
                intval($formatString[9]),
                intval($formatString[10]),
                intval($formatString[11]),
                intval($formatString[12]),
                intval($formatString[13]),
                intval($formatString[14])
            ],
        ];

        $formatPatternBottomLeft = [
            [intval($formatString[6])],
            [intval($formatString[5])],
            [intval($formatString[4])],
            [intval($formatString[3])],
            [intval($formatString[2])],
            [intval($formatString[1])],
            [intval($formatString[0])],
        ];

        $this->addPatternToMatrix($formatPatternTopLeft, row: 0, column: 0, overwrite: true);
        $this->addPatternToMatrix($formatPatternTopRight, row: 8, column: $this->matrixSize - 8, overwrite: true);
        $this->addPatternToMatrix($formatPatternBottomLeft, row: $this->matrixSize - 7, column: 8, overwrite: true);
    }

    /**
     * Adds the version string to the upper right and lower left matrix corners for versions 7 and higher
     *
     * @return void
     * @throws LuxiQRException
     */
    protected function addVersionString(): void
    {
        if ($this->version < 7) return;

        $versionString = $this->getVersionString();

        $versionPatternBottomLeft = [
            [
                intval($versionString[0]),
                intval($versionString[3]),
                intval($versionString[6]),
                intval($versionString[9]),
                intval($versionString[12]),
                intval($versionString[15])
            ],
            [
                intval($versionString[1]),
                intval($versionString[4]),
                intval($versionString[7]),
                intval($versionString[10]),
                intval($versionString[13]),
                intval($versionString[16])
            ],
            [
                intval($versionString[2]),
                intval($versionString[5]),
                intval($versionString[8]),
                intval($versionString[11]),
                intval($versionString[14]),
                intval($versionString[17])
            ],
        ];

        $versionPatternTopRight = [
            [intval($versionString[0]), intval($versionString[1]), intval($versionString[2])],
            [intval($versionString[3]), intval($versionString[4]), intval($versionString[5])],
            [intval($versionString[6]), intval($versionString[7]), intval($versionString[8])],
            [intval($versionString[9]), intval($versionString[10]), intval($versionString[11])],
            [intval($versionString[12]), intval($versionString[13]), intval($versionString[14])],
            [intval($versionString[15]), intval($versionString[16]), intval($versionString[17])],
        ];

        $this->addPatternToMatrix($versionPatternBottomLeft, row: $this->matrixSize - 11, column: 0, overwrite: true);
        $this->addPatternToMatrix($versionPatternTopRight, row: 0, column: $this->matrixSize - 11, overwrite: true);
    }

    /**
     * Returns a format string containing the error correction level and the mask number
     *
     * @return string
     * @throws LuxiQRException
     */
    protected function getFormatString(): string
    {
        return match ($this->eccLevel) {
            ErrorCorrection::LOW => match ($this->maskVersion) {
                0 => "111011111000100",
                1 => "111001011110011",
                2 => "111110110101010",
                3 => "111100010011101",
                4 => "110011000101111",
                5 => "110001100011000",
                6 => "110110001000001",
                7 => "110100101110110",
                default => throw new LuxiQRException("Invalid mask version: {$this->maskVersion}")
            },
            ErrorCorrection::MEDIUM => match ($this->maskVersion) {
                0 => "101010000010010",
                1 => "101000100100101",
                2 => "101111001111100",
                3 => "101101101001011",
                4 => "100010111111001",
                5 => "100000011001110",
                6 => "100111110010111",
                7 => "100101010100000",
                default => throw new LuxiQRException("Invalid mask version: {$this->maskVersion}")
            },
            ErrorCorrection::QUARTILE => match ($this->maskVersion) {
                0 => "011010101011111",
                1 => "011000001101000",
                2 => "011111100110001",
                3 => "011101000000110",
                4 => "010010010110100",
                5 => "010000110000011",
                6 => "010111011011010",
                7 => "010101111101101",
                default => throw new LuxiQRException("Invalid mask version: {$this->maskVersion}")
            },
            ErrorCorrection::HIGH => match ($this->maskVersion) {
                0 => "001011010001001",
                1 => "001001110111110",
                2 => "001110011100111",
                3 => "001100111010000",
                4 => "000011101100010",
                5 => "000001001010101",
                6 => "000110100001100",
                7 => "000100000111011",
                default => throw new LuxiQRException("Invalid mask version: {$this->maskVersion}")
            },
            default => throw new LuxiQRException("Invalid ECC levels: {$this->eccLevel}")
        };
    }

    /**
     * Returns an encoded string containing the version number
     *
     * @return string
     * @throws LuxiQRException
     */
    protected function getVersionString(): string
    {
        return match ($this->version) {
            7 => "000111110010010100",
            8 => "001000010110111100",
            9 => "001001101010011000",
            10 => "001010010011010010",
            11 => "001011101111110110",
            12 => "001100011101100010",
            13 => "001101100001000110",
            14 => "001110011000001100",
            15 => "001111100100101000",
            16 => "010000101101111000",
            17 => "010001010001011100",
            18 => "010010101000010100",
            19 => "010011010100110000",
            20 => "010100100110100100",
            21 => "010101011010000000",
            22 => "010110100011001000",
            23 => "010111011111101100",
            24 => "011000111011000100",
            25 => "011001000111100000",
            26 => "011010111110101000",
            27 => "011011000010001100",
            28 => "011100110000011000",
            29 => "011101001100111100",
            30 => "011110110101110100",
            31 => "011111001001010000",
            32 => "100000100111010000",
            33 => "100001011011110000",
            34 => "100010100010111000",
            35 => "100011011110011000",
            36 => "100100101100001000",
            37 => "100101010000101000",
            38 => "100110101001100000",
            39 => "100111010101000000",
            40 => "101000110001101000",
            default => throw new LuxiQRException("Invalid version: {$this->version}")
        };
    }

    /**
     * Detects if the data will fit in the specified QR code version
     *
     * @param bool $forceUpdate
     * @return bool
     */
    protected function dataFitsInMatrix(bool $forceUpdate = true): bool
    {
        if (strlen($this->data) <= CapacityTables::CHARACTER[$this->version][$this->eccLevel][$this->mode]) {
            return true;
        }

        if (!$forceUpdate) {
            return false;
        }

        $this->detectVersion();

        return true;
    }
}