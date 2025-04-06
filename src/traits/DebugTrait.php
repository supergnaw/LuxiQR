<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR\traits;

trait DebugTrait
{
    /**
     * Prints out bits broken up with spaces for easy identificaiton of bytes, groups, or sections
     *
     * @param string $bits
     * @param int $breakPoint
     * @param int $newLine
     * @return string
     */
    public function debugBits(string $bits = "", int $breakPoint = 8, int $newLine = 8): string
    {
        $output = "";
        $group = 0;
        for ($i = 0; $i < strlen($bits); $i += $breakPoint) {
            $output .= substr($bits, $i, $breakPoint) . " ";
            $group++;

            if ($group == $newLine) {
                $output .= "<br>\n";
                $group = 0;
            }
        }

        return "<p><span style='font-family: monospace;'>$output</span></p>";
    }

    /**
     * Prints a variable value with a debug message
     *
     * @param null $var
     * @param string $message
     * @return string
     */
    public function debugVar($var = null, string $message = ""): string
    {
        $message = ($message) ? "$message" : "debug var";
        return "<p>$message:<br><span style='font-family: monospace;'>$var</span></p>";
    }

    /**
     * Prints an array value with a debug message
     *
     * @param array $array
     * @param string $message
     * @return string
     */
    public function debugArray(array $array = [], string $message = ""): string
    {
        $message = ($message) ? "$message" : "debug array";
        $array = implode(", ", $array);
        return "<p>$message:<br><span style='font-family: monospace;'>[$array]</span></p>";
    }

    /**
     * Converts an array to a string
     *
     * @param array $array
     * @return string
     */
    public function arr2Str(array $array = []): string
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->arr2Str($value);
            }
        }

        return "[" . implode(", ", $array) . "]";
    }

    /**
     * Generates random bits for a given version of QR code
     *
     * @param int $length
     * @return string
     */
    public function generateRandomBits(int $length = 8): string
    {
        $bits = "";

        for ($i = 0; $i < $length; $i++) $bits .= rand(min: 0, max: 1);

        return $bits;
    }

    public function debugStr(string $string): void
    {
        echo "<p>$string</p>";
    }
}