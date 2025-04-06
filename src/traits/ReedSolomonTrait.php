<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR\traits;

use supergnaw\LuxiQR\constants\CapacityTables;
use supergnaw\LuxiQR\constants\Padding;
use supergnaw\LuxiQR\exception\LuxiQRException;

trait ReedSolomonTrait
{
    /**
     * Splits data into blocks based on code version and error correction level
     *
     * @param string|null $encodedData
     * @param int|null $version
     * @param string|null $eccLevel
     * @return array
     */
    protected function splitDataBlocks(): void
    {
        $encodedData = $this->bitsToBytes($this->encodedData);

        $groups = CapacityTables::BYTE[$this->version][$this->eccLevel]["groups"];

        $dataBlocks = [];

        $offset = 0;

        foreach ($groups as $group) {
            for ($block = 0; $block < $group["blocks"]; $block++) {
                $dataBlocks[] = array_slice($encodedData, offset: $offset, length: $group["size"]);
                $offset += $group["size"];
            }
        }

        $this->dataBlocks = $dataBlocks;
    }

    /**
     * Generates error correction bytes for each data block
     *
     * @param array $blocks
     * @return array
     */
    protected function generateEccBlocks(): void
    {
        $eccBlocks = [];

        $degree = CapacityTables::BYTE[$this->version][$this->eccLevel]["ecc"];

        $generator = $this->getGeneratorPolynomial(degree: $degree);

        foreach ($this->dataBlocks as $block) {
            // Create a copy of the block that we can modify
            $augmentedBlock = array_merge($block, array_fill(0, $degree, 0));

            $remainder = $this->dividePolynomials($augmentedBlock, $generator);

            $eccBlocks[] = $remainder;
        }

        $this->eccBlocks = $eccBlocks;
    }

    /**
     * Interleaves the data codeword and error correction codeword blocks
     *
     * @return void
     */
    protected function interleaveBlocks(): void
    {
        $interleaved = [];

        $maxDataLength = max(array_map("count", $this->dataBlocks));
        $maxEccLength = max(array_map("count", $this->eccBlocks));

        // interleave data blocks
        for ($i = 0; $i < $maxDataLength; $i++) {
            foreach ($this->dataBlocks as $block) {
                if (!isset($block[$i])) continue;

                $interleaved[] = $block[$i];
            }
        }

        // interleave ecc blocks
        for ($i = 0; $i < $maxEccLength; $i++) {
            foreach ($this->eccBlocks as $block) {
                if (!isset($block[$i])) continue;

                $interleaved[] = $block[$i];
            }
        }

        // get remainder bits
        $remainderBits = match ($this->version) {
            1, 7, 8, 9, 10, 11, 12, 13, 35, 36, 37, 38, 39, 40 => "",
            2, 3, 4, 5, 6 => "0000000",
            14, 15, 16, 17, 18, 19, 20, 28, 29, 30, 31, 32, 33, 34 => "000",
            21, 22, 23, 24, 25, 26, 27 => "0000",
            default => throw new LuxiQRException("Invalid version for remainder bits")
        };

        $this->interleavedBlocks = $interleaved;
        $this->bitstream = $this->bytesToBits($this->interleavedBlocks) . $remainderBits;
    }

    /**
     * Converts a bit string into an array of integer bytes
     *
     * @param string $bits
     * @return array
     */
    protected function bitsToBytes(string $bits): array
    {
        if (0 !== strlen($bits) % 8) {
            throw new LuxiQRException("Number of bits is not a multiple of 8: " . strlen($bits));
        }

        $bytes = [];

        for ($i = 0; $i < strlen($bits); $i += 8) {
            $bytes[] = bindec(substr($bits, $i, 8));
        }

        return $bytes;
    }

    /**
     * Converts an array of integer bytes to a bit string
     *
     * @param array $bytes
     * @return string
     */
    protected function bytesToBits(array $bytes): string
    {
        $bits = "";

        foreach ($bytes as $byte) {
            $bits .= str_pad(
                string: decbin($byte),
                length: 8,
                pad_string: Padding::DATA,
                pad_type: STR_PAD_LEFT
            );
        }

        return $bits;
    }
}