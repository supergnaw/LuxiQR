<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR;

trait StructureTrait
{
    protected function generateMatrix(): void
    {
        $this->initializeMatrix();
        $this->addDataToMatrix();
        $this->applyBestMask();
    }

    /**
     * Initializes the module matrix as a two-dimensional array and adds the reserved patterns
     *
     * @return void
     */
    private function initializeMatrix(): void
    {
        $this->moduleMatrix = [];
        $this->maskMask = [];
        $this->matrixSize = $this->version * 4 + 17;

        for ($i = 0; $i < $this->matrixSize; $i++) {
            $this->moduleMatrix[] = array_fill(start_index: 0, count: $this->matrixSize, value: null);
            $this->maskMask[] = array_fill(start_index: 0, count: $this->matrixSize, value: false);
        }

        // add reserved patterns
        $this->addFinderPatterns();
        $this->addTimingPatterns();
        $this->addAlignmentPatterns();
        $this->addVersionString();
    }

    /**
     * Adds data bits to module matrix
     *
     * @return void
     */
    private function addDataToMatrix(): void
    {
        $d = 0;

        for ($x = count($this->moduleMatrix) - 1; $x >= 0; $x -= 4) {
            // if we ever reach the end of the string we dun goof'd
//            if ($d >= strlen($this->bitstream)) throw new LuxiQRException("Bitstream exceeds module matrix capacity: $d");

            // go up
            for ($y = count($this->moduleMatrix) - 1; $y >= 0; $y--) {
                if (!is_numeric($this->moduleMatrix[$y][$x])) {
//                    if ($d >= strlen($this->bitstream)) break;
                    $this->moduleMatrix[$y][$x] = intval(substr($this->bitstream, $d, 1));
                    $this->maskMask[$y][$x] = true;
                    $d++;
                }

                if (!is_numeric($this->moduleMatrix[$y][$x - 1])) {
//                    if ($d >= strlen($this->bitstream)) break;
                    $this->moduleMatrix[$y][$x - 1] = intval(substr($this->bitstream, $d, 1));
                    $this->maskMask[$y][$x - 1] = true;
                    $d++;
                }

            }

            // exception to skip timing pattern column completely
            if (8 == $x) $x--;

            // go down
            for ($y = 0; $y <= count($this->moduleMatrix) - 1; $y++) {
                if (0 > $x - 2) break;
                if (!is_numeric($this->moduleMatrix[$y][$x - 2])) {
//                    if ($d >= strlen($this->bitstream)) break;
                    $this->moduleMatrix[$y][$x - 2] = intval(substr($this->bitstream, $d, 1));
                    $this->maskMask[$y][$x - 2] = true;
                    $d++;
                }

                if (!is_numeric($this->moduleMatrix[$y][$x - 3])) {
//                    if ($d >= strlen($this->bitstream)) break;
                    $this->moduleMatrix[$y][$x - 3] = intval(substr($this->bitstream, $d, 1));
                    $this->maskMask[$y][$x - 3] = true;
                    $d++;
                }
            }
        }
    }

    /**
     * Adds a 2-dimensional array pattern to the module matrix
     *
     * @param array $pattern
     * @param int $row
     * @param int $column
     * @param bool $overwrite
     * @param bool $allowMask
     * @return void
     */
    private function addPatternToMatrix(array $pattern,
                                       int   $row,
                                       int   $column,
                                       bool  $overwrite = false,
                                       bool  $allowMask = false): void
    {
        // TODO: this might be causing the matrix to enlarge down and right if the pattern extends beyond the bounds
        // define outer bounds
        $boundLeft = 0;
        $boundRight = count($this->moduleMatrix) - 1;
        $boundTop = 0;
        $boundBottom = count($this->moduleMatrix) - 1;

        for ($r = 0; $r < count($pattern); $r++) {
            for ($c = 0; $c < count($pattern[$r]); $c++) {
                // skip null values in patterns
                if (is_null($pattern[$r][$c])) continue;

                // calculate target coordinates
                $x = $c + $column;
                $y = $r + $row;

                // skip if out of bounds
                if ($x < $boundLeft or $x > $boundRight) continue;
                if ($y < $boundTop or $y > $boundBottom) continue;


                // skip if already filled
                if (!$overwrite && is_numeric($this->moduleMatrix[$y][$x])) continue;

                // add data module
                $this->moduleMatrix[$y][$x] = $pattern[$r][$c];
                if ($allowMask) $this->maskMask[$y][$x] = true;
            }
        }
    }

    /**
     * Adds the finder patterns in the top left, top right, and bottom left corners, along with the dedicated dark
     * module at (9, size - 9)
     *
     * @return void
     */
    private function addFinderPatterns(): void
    {
        $finderPattern = [
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 1],
            [0, 1, 1, 1, 1, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 0, 0, 1, 0, 0],
            [0, 1, 0, 1, 1, 1, 0, 1, 0, 0],
            [0, 1, 0, 1, 1, 1, 0, 1, 0, 0],
            [0, 1, 0, 1, 1, 1, 0, 1, 0, 0],
            [0, 1, 0, 0, 0, 0, 0, 1, 0, 0],
            [0, 1, 1, 1, 1, 1, 1, 1, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ];
        $this->addPatternToMatrix(pattern: $finderPattern, row: -1, column: -1);
        $this->addPatternToMatrix(pattern: $finderPattern, row: -1, column: $this->matrixSize - 8);
        $this->addPatternToMatrix(pattern: $finderPattern, row: $this->matrixSize - 8, column: -1);
    }

    /**
     * Adds the alternating 1, 0, 1, 0... timing patterns between the finder patterns
     *
     * @return void
     */
    private function addTimingPatterns(): void
    {
        $length = $this->matrixSize - 16;
        $horizontal = [[]];
        $vertical = [];

        for ($i = 0; $i < $length; $i++) {
            $horizontal[0][] = [1, 0][$i % 2];
            $vertical[] = [[1, 0][$i % 2]];
        }

        $this->addPatternToMatrix(pattern: $horizontal, row: 6, column: 8, overwrite: true);
        $this->addPatternToMatrix(pattern: $vertical, row: 8, column: 6, overwrite: true);
    }

    /**
     * Adds the alignment patterns within the module matrix
     *
     * @return void
     */
    private function addAlignmentPatterns(): void
    {
        $coords = match ($this->version) {
            2 => [6, 18],
            3 => [6, 22],
            4 => [6, 26],
            5 => [6, 30],
            6 => [6, 34],
            7 => [6, 22, 38],
            8 => [6, 24, 42],
            9 => [6, 26, 46],
            10 => [6, 28, 50],
            11 => [6, 30, 54],
            12 => [6, 32, 58],
            13 => [6, 34, 62],
            14 => [6, 26, 46, 66],
            15 => [6, 26, 48, 70],
            16 => [6, 26, 50, 74],
            17 => [6, 30, 54, 78],
            18 => [6, 30, 56, 82],
            19 => [6, 30, 58, 86],
            20 => [6, 34, 62, 90],
            21 => [6, 28, 50, 72, 94],
            22 => [6, 26, 50, 74, 98],
            23 => [6, 30, 54, 78, 102],
            24 => [6, 28, 54, 80, 106],
            25 => [6, 32, 58, 84, 110],
            26 => [6, 30, 58, 86, 114],
            27 => [6, 34, 62, 90, 118],
            28 => [6, 26, 50, 74, 98, 122],
            29 => [6, 30, 54, 78, 102, 126],
            30 => [6, 26, 52, 78, 104, 130],
            31 => [6, 30, 56, 82, 108, 134],
            32 => [6, 34, 60, 86, 112, 138],
            33 => [6, 30, 58, 86, 114, 142],
            34 => [6, 34, 62, 90, 118, 146],
            35 => [6, 30, 54, 78, 102, 126, 150],
            36 => [6, 24, 50, 76, 102, 128, 154],
            37 => [6, 28, 54, 80, 106, 132, 158],
            38 => [6, 32, 58, 84, 110, 136, 162],
            39 => [6, 26, 54, 82, 110, 138, 166],
            40 => [6, 30, 58, 86, 114, 142, 170],
            default => []
        };

        $alignmentPattern = [
            [1, 1, 1, 1, 1],
            [1, 0, 0, 0, 1],
            [1, 0, 1, 0, 1],
            [1, 0, 0, 0, 1],
            [1, 1, 1, 1, 1],
        ];

        for ($r = 0; $r < count($coords); $r++) {
            for ($c = 0; $c < count($coords); $c++) {
                // alignment patterns do not overlap finder patterns
                if ((count($coords) - 1 == $r and 0 == $c) // bottom left
                    || (0 == $r and count($coords) - 1 == $c) // top right
                    || (0 == $r and 0 == $c)) continue; // top left

                $this->addPatternToMatrix($alignmentPattern, $coords[$c] - 2, $coords[$r] - 2);
            }
        }
    }

    private function addBorder(): void
    {
        // TODO: the QR code standard says to add a border of 4 white modules on all sides
    }
}

/*

5-Q

(codeword #1) 01000011
(codeword #2) 01010101
(codeword #3) 01000110
(codeword #4) 10000110
(codeword #5) 01010111
(codeword #6) 00100110
(codeword #7) 01010101
(codeword #8) 11000010
(codeword #9) 01110111
(codeword #10) 00110010
(codeword #11) 00000110
(codeword #12) 00010010
(codeword #13) 00000110
(codeword #14) 01100111
(codeword #15) 00100110
(codeword #16) 11110110
(codeword #17) 11110110
(codeword #18) 01000010
(codeword #19) 00000111
(codeword #20) 01110110
(codeword #21) 10000110
(codeword #22) 11110010
(codeword #23) 00000111
(codeword #24) 00100110
(codeword #25) 01010110
(codeword #26) 00010110
(codeword #27) 11000110
(codeword #28) 11000111
(codeword #29) 10010010
(codeword #30) 00000110
(codeword #31) 10110110
(codeword #32) 11100110
(codeword #33) 11110111
(codeword #34) 01110111
(codeword #35) 00110010
(codeword #36) 00000111
(codeword #37) 01110110
(codeword #38) 10000110
(codeword #39) 01010111
(codeword #40) 00100110
(codeword #41) 01010010
(codeword #42) 00000110
(codeword #43) 10000110
(codeword #44) 10010111
(codeword #45) 00110010
(codeword #46) 00000111
(codeword #47) 01000110
(codeword #48) 11110111
(codeword #49) 01110110
(codeword #50) 01010110
(codeword #51) 11000010
(codeword #52) 00000110
(codeword #53) 10010111
(codeword #54) 00110010
(codeword #55) 11100000
(codeword #56) 11101100
(codeword #57) 00010001
(codeword #58) 11101100
(codeword #59) 00010001
(codeword #60) 11101100
(codeword #61) 00010001
(codeword #62) 11101100
=======================

(codeword #1) 0100____

Mode = 0100, "Byte Mode"

-----------------------

(codeword #1) ____0011
(codeword #2) 0101____

Count indicator = 00110101, 53

-----------------------

(codeword #2) ____0101
(codeword #3) 01000110
(codeword #4) 10000110
(codeword #5) 01010111
(codeword #6) 00100110
(codeword #7) 01010101
(codeword #8) 11000010
(codeword #9) 01110111
(codeword #10) 00110010
(codeword #11) 00000110
(codeword #12) 00010010
(codeword #13) 00000110
(codeword #14) 01100111
(codeword #15) 00100110
(codeword #16) 11110110
(codeword #17) 11110110
(codeword #18) 01000010
(codeword #19) 00000111
(codeword #20) 01110110
(codeword #21) 10000110
(codeword #22) 11110010
(codeword #23) 00000111
(codeword #24) 00100110
(codeword #25) 01010110
(codeword #26) 00010110
(codeword #27) 11000110
(codeword #28) 11000111
(codeword #29) 10010010
(codeword #30) 00000110
(codeword #31) 10110110
(codeword #32) 11100110
(codeword #33) 11110111
(codeword #34) 01110111
(codeword #35) 00110010
(codeword #36) 00000111
(codeword #37) 01110110
(codeword #38) 10000110
(codeword #39) 01010111
(codeword #40) 00100110
(codeword #41) 01010010
(codeword #42) 00000110
(codeword #43) 10000110
(codeword #44) 10010111
(codeword #45) 00110010
(codeword #46) 00000111
(codeword #47) 01000110
(codeword #48) 11110111
(codeword #49) 01110110
(codeword #50) 01010110
(codeword #51) 11000010
(codeword #52) 00000110
(codeword #53) 10010111
(codeword #54) 00110010
(codeword #55) 1110____

01010100 01101000 01100101 01110010 01100101 01011100 00100111 01110011
00100000 01100001 00100000 01100110 01110010 01101111 01101111 01100100
00100000 01110111 01101000 01101111 00100000 01110010 01100101 01100001
01101100 01101100 01111001 00100000 01101011 01101110 01101111 01110111
01110011 00100000 01110111 01101000 01100101 01110010 01100101 00100000
01101000 01101001 01110011 00100000 01110100 01101111 01110111 01100101
01101100 00100000 01101001 01110011 00101110

*/
