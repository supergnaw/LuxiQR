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
    protected function splitDataBlocks(string $encodedData, int $version = null, string $eccLevel = null): array
    {
        $version = $version ?? $this->version;
        $eccLevel = $eccLevel ?? $this->eccLevel;

        $encodedData = $this->bitsToBytes($encodedData);

        $groups = self::BYTE_COUNT_TABLE[$version][$eccLevel]["groups"];

        $chunks = [];
        $offset = 0;

        foreach ($groups as $group) {
            for ($block = 0; $block < $group["blocks"]; $block++) {
                $chunks[] = array_slice($encodedData, offset: $offset, length: $group["size"]);
                $offset += $group["size"];
            }
        }

        return $chunks;
    }

    /**
     * Generates error correction bytes for each data block
     *
     * @param array $blocks
     * @return array
     */
    protected function generateEccBlocks(array $blocks): array
    {
        $eccBlocks = [];

        $eccBytes = self::BYTE_COUNT_TABLE[$this->version][$this->eccLevel]["ecc"];
        $generator = $this->getGeneratorPolynomial(degree: $eccBytes - 1);

        foreach ($blocks as $block) {
            $eccBlocks[] = $this->dividePolynomials($block, $generator);
        }

        return $eccBlocks;
    }

    protected function interleaveBlocks(array $dataBlocks, array $eccBlocks): array
    {
        $interleaved = [];

        $maxDataLength = max(array_map("count", $dataBlocks));
        $maxEccLength = max(array_map("count", $eccBlocks));

        // interleave data blocks
        for ($i = 0; $i < $maxDataLength; $i++) {
            foreach ($dataBlocks as $block) {
                if (!isset($block[$i])) continue;

                $interleaved[] = $block[$i];
            }
        }

        // interleave ecc blocks
        for ($i = 0; $i < $maxEccLength; $i++) {
            foreach ($eccBlocks as $block) {
                if (!isset($block[$i])) continue;

                $interleaved[] = $block[$i];
            }
        }

        return $interleaved;
    }


//     This was the old function and it didn't work so I gave up and started over with generateECCBlocks() instead
//    protected function deprecated_getErrorCorrectionCodewords(array $dataCodewords): array
//    {
//        $eccCount = self::BYTE_COUNT_TABLE[$this->version][$this->eccLevel]['ecc'];
//
//        $generatorPoly = $this->getGeneratorPolynomial($eccCount);
//        $ecCodewords = array_fill(0, $eccCount, 0);
//
//        foreach ($dataCodewords as $dataCodeword) {
//            // XOR the current byte with the first codeword
//            $term = $dataCodeword ^ $ecCodewords[0];
//
//            // shift codewords to the left
//            array_shift($ecCodewords);
//
//            // XOR and assign the generator polynomial and term product with the codewords
//            for ($i = 0; $i < count($generatorPoly); $i++) {
//                $product = $this->galoisFieldMultiply($term, $generatorPoly[$i]);
//                // i forget why I'm XORing with zero, but 0 XOR'd with 0 is 1, so that might be why
//                $ecCodewords[$i] ^= $product;
//                /*
//                try {
//                    $product = $this->galoisFieldMultiply($term, $generatorPoly[$i]);
//                    $ecCodewords[$i] ^= $product;
//                } catch (\Exception $e) {
//                    var_dump($i);
//                    var_dump($e->getMessage());
//                    var_dump(count($ecCodewords));
//                    var_dump(count($generatorPoly));
//                }/**/
//            }
//        }
//
//        return $ecCodewords;
//    }
}