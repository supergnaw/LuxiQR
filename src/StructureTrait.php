<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR;

trait StructureTrait
{
    protected function generateMatrix(): void
    {
        if (empty($this->payload)) $this->generatePayload();

        $this->buildModuleMatrix();
        $this->addDataToMatrix($this->payload);
        $this->applyBestMask();

        $this->payload = "";
    }

    /**
     * Detects the number of bits for the count indicator
     *
     * @param int|null $version
     * @param string|null $mode
     * @return int
     */
    protected function detectCountIndicatorSize(int $version = null, string $mode = null): int
    {
        $version = $version ?? $this->version;
        $mode = $mode ?? $this->mode;

        return match ($version <= 9 ? 9 : ($version <= 26 ? 26 : 40)) {
            9 => [self::NUMERIC => 10, self::ALPHANUMERIC => 9, self::BYTE => 8, self::KANJI => 8][$mode],
            26 => [self::NUMERIC => 12, self::ALPHANUMERIC => 11, self::BYTE => 16, self::KANJI => 10][$mode],
            40 => [self::NUMERIC => 14, self::ALPHANUMERIC => 13, self::BYTE => 16, self::KANJI => 12][$mode],
        };
    }

    /**
     * Initializes the module matrix as a two-dimensional array and adds the reserved patterns
     *
     * @return void
     */
    protected function buildModuleMatrix(): void
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
     * Adds a 2-dimensional array pattern to the module matrix
     *
     * @param array $pattern
     * @param int $row
     * @param int $column
     * @param bool $overwrite
     * @param bool $allowMask
     * @return void
     */
    protected function addPatternToMatrix(array $pattern,
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
    protected function addFinderPatterns(): void
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
    protected function addTimingPatterns(): void
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
    protected function addAlignmentPatterns(): void
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

    /**
     * Adds data bits to $this->moduleMatrix
     *
     * @param $data
     * @return void
     */
    protected function addDataToMatrix($data): void
    {
        $this->buildModuleMatrix();

        $d = 0;

        for ($x = count($this->moduleMatrix) - 1; $x >= 0; $x -= 4) {
            // if we ever reach the end of the string, stop processing, but this shouldn't ever happen
            if ($d >= strlen($data)) break;

            // go up
            for ($y = count($this->moduleMatrix) - 1; $y >= 0; $y--) {
                if (!is_numeric($this->moduleMatrix[$y][$x])) {
                    if ($d >= strlen($data)) break;
                    $this->moduleMatrix[$y][$x] = intval(substr($data, $d, 1));
                    $this->maskMask[$y][$x] = true;
                    $d++;
                }

                if (!is_numeric($this->moduleMatrix[$y][$x - 1])) {
                    if ($d >= strlen($data)) break;
                    $this->moduleMatrix[$y][$x - 1] = intval(substr($data, $d, 1));
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
                    if ($d >= strlen($data)) break;
                    $this->moduleMatrix[$y][$x - 2] = intval(substr($data, $d, 1));
                    $this->maskMask[$y][$x - 2] = true;
                    $d++;
                }

                if (!is_numeric($this->moduleMatrix[$y][$x - 3])) {
                    if ($d >= strlen($data)) break;
                    $this->moduleMatrix[$y][$x - 3] = intval(substr($data, $d, 1));
                    $this->maskMask[$y][$x - 3] = true;
                    $d++;
                }
            }
        }
    }
}