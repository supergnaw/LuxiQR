<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR;

trait MaskTrait
{
    /**
     * Applies the mask with the lowest penalty score to $this->moduleMatrix
     *
     * @return void
     */
    protected function applyBestMask(): void
    {
        $bestMask = null;
        $lowestPenalty = null;

        for ($maskVersion = 0; $maskVersion <= 7; $maskVersion++) {
            $penalty = $this->calculatePenaltyScore($maskVersion);

            if (!$lowestPenalty || $lowestPenalty > $penalty) {
                $lowestPenalty = $penalty;
                $bestMask = $maskVersion;
            }
        }

        $this->maskVersion = $bestMask;
        $this->addFormatString();
        $this->applyMask($bestMask);
        $this->maskVersion = $maskVersion;
    }

    /**
     * Applies mask to provided module matrix, or $this->moduleMatrix if no matrix is provided
     *
     * @param int $maskVersion
     * @param array|null $moduleMatrix
     * @return array
     */
    private function applyMask(int $maskVersion, array $moduleMatrix = null): array
    {
        $maskVersion = min(7, max(0, $maskVersion));
        $maskedModuleMatrix = $moduleMatrix ?? $this->moduleMatrix;

        for ($row = 0; $row < count($maskedModuleMatrix); $row++) {
            for ($column = 0; $column < count($maskedModuleMatrix[$row]); $column++) {
                if (!$this->maskBit($row, $column, $maskVersion)) continue;

                $maskedModuleMatrix[$row][$column] = (0 == $maskedModuleMatrix[$row][$column]) ? 1 : 0;
            }
        }

        return (null === $moduleMatrix) ? $this->moduleMatrix = $maskedModuleMatrix : $maskedModuleMatrix;
    }

    /**
     * Returns true if module should be masked, otherwise false
     *
     * @param int $row
     * @param int $column
     * @param int $maskVersion
     * @return bool
     */
    private function maskBit(int $row, int $column, int $maskVersion): bool
    {
        if (!$this->maskMask[$row][$column]) return false;

        return match ($maskVersion) {
            0 => 0 == ($row + $column) % 2,
            1 => 0 == ($row) % 2,
            2 => 0 == ($column) % 3,
            3 => 0 == ($row + $column) % 3,
            4 => 0 == (floor($row / 2) + floor($column / 3)) % 2,
            5 => 0 == (($row * $column) % 2) + (($row * $column) % 3),
            6 => 0 == ((($row * $column) % 2) + (($row * $column) % 3)) % 2,
            7 => 0 == ((($row + $column) % 2) + (($row * $column) % 3)) % 2,
            default => false
        };
    }

    /**
     * Calculates the penalty score for a given mask
     *
     * @param int $maskVersion
     * @return int
     */
    private function calculatePenaltyScore(int $maskVersion): int
    {
        if (0 > $maskVersion or 7 < $maskVersion) {
            throw new LuxiQRException("Mask version $maskVersion is out of range");
        }

        $this->maskVersion = $maskVersion;

        // curiously enough, even though the format string is not masked, it is included in the calculation of the
        // penalty score, thus we need to apply the mask-specific format string to properly calculate it
        $this->addFormatString();

        $maskedModuleMatrix = $this->applyMask($this->maskVersion, $this->moduleMatrix);

        return
            $this->evaluateConsecutiveModules($maskedModuleMatrix) +
            $this->evaluateLargeAreas($maskedModuleMatrix) +
            $this->evaluateFinderPatterns($maskedModuleMatrix) +
            $this->evaluateDarkLightRatio($maskedModuleMatrix);
    }

    /**
     * Evaluates module matrix for consecituve identical modules, horizontal or vertical, and increases the penalty
     * value by n - 2, where n is the number of consecutive modules over 5
     *
     * @param array $moduleMatrix
     * @return int
     */
    private function evaluateConsecutiveModules(array $moduleMatrix): int
    {
        $penalty = 0;

        preg_match_all('/0{5,}|1{5,}/', $this->getRowColStrings($moduleMatrix), $matches);

        foreach ($matches[0] as $match) $penalty += strlen($match) - 2;

        return $penalty;
    }

    /**
     * Evaluates for module areas of 2x2 or larger of the same value, including overlap, increasing the penalty by 3 for
     * each 2x2 area found
     *
     * @param array $moduleMatrix
     * @return int
     */
    private function evaluateLargeAreas(array $moduleMatrix): int
    {
        $penalty = 0;

        for ($row = 0; $row < count($moduleMatrix) - 1; $row++) {
            for ($column = 0; $column < count($moduleMatrix[$row]) - 1; $column++) {
                if ($moduleMatrix[$row + 1][$column] != $moduleMatrix[$row][$column]) continue;
                if ($moduleMatrix[$row][$column + 1] != $moduleMatrix[$row][$column]) continue;
                if ($moduleMatrix[$row + 1][$column + 1] != $moduleMatrix[$row][$column]) continue;

                $penalty += 3;
            }
        }

        return $penalty;
    }

    /**
     * Evaluates for module patterns similar to finder patterns with 4 white modules on either side, increasing the
     * penalty by 40 for each match
     *
     * @param array $moduleMatrix
     * @return int
     */
    private function evaluateFinderPatterns(array $moduleMatrix): int
    {
        preg_match_all('/10111010000|00001011101/', $this->getRowColStrings($moduleMatrix), $matches);

        return count($matches[0]) * 40;
    }

    /**
     * Evaluates dark to light module count ratio and calculates the penalty by some sort of witchcraft
     *
     * @param array $moduleMatrix
     * @return int
     */
    private function evaluateDarkLightRatio(array $moduleMatrix): int
    {
        $allModules = "";

        for ($row = 0; $row < count($moduleMatrix) - 1; $row++) {
            $allModules .= implode("", $moduleMatrix[$row]);
        }

        $darkModulePercent = intval(substr_count($allModules, "1") / strlen($allModules) * 100);

        $lower = abs((floor($darkModulePercent / 5) * 5) - 50) / 5;
        $upper = abs((ceil($darkModulePercent / 5) * 5) - 50) / 5;

        return intval(min($lower, $upper) * 10);
    }

    /**
     * Helper function to get each row and column as strings
     *
     * @param array $moduleMatrix
     * @return string
     */
    private function getRowColStrings(array $moduleMatrix): string
    {
        $rowStrings = array_fill(start_index: 0, count: count($moduleMatrix), value: "");
        $columnStrings = array_fill(start_index: 0, count: count($moduleMatrix[0]), value: "");

        for ($row = 0; $row < count($moduleMatrix) - 1; $row++) {
            for ($column = 0; $column < count($moduleMatrix[$row]) - 1; $column++) {
                $rowStrings[$row] .= $moduleMatrix[$row][$column];
                $columnStrings[$column] .= $moduleMatrix[$row][$column];
            }
        }

        return implode("\m", $rowStrings) ."\n". implode("\n", $columnStrings);
    }
}