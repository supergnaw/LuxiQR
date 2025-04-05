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
    protected function generateEccBlocksV1(): void
    {
        $eccBlocks = [];

        $degree = self::BYTE_COUNT_TABLE[$this->version][$this->eccLevel]["ecc"] - 1;

        $generator = $this->getGeneratorPolynomial(degree: $degree);

        foreach ($this->dataBlocks as $block) {
            $remainder = $this->dividePolynomials($block, $generator);

            $eccBlocks[] = $remainder;
        }

        $this->eccBlocks = $eccBlocks;
    }

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
    }
}