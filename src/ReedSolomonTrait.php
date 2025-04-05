<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR;

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

        $groups = self::BYTE_COUNT_TABLE[$this->version][$this->eccLevel]["groups"];

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

        $degree = self::BYTE_COUNT_TABLE[$this->version][$this->eccLevel]["ecc"];

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

        $this->interleavedBlocks = $interleaved;
        $this->bitstream = $this->bytesToBits($this->interleavedBlocks) . self::REMAINDER_BITS[$this->version];
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
                pad_string: self::PAD_DATA,
                pad_type: STR_PAD_LEFT
            );
        }

        return $bits;
    }
}