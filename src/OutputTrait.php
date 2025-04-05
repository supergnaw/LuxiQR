<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR;

trait OutputTrait
{
    /**
     * Outputs the QR code as a raw string of bits
     *
     * @param bool $removeNewlines
     * @return string
     */
    public function outputRawString(bool $removeNewlines = false): string
    {
        $newlines = ($removeNewlines === true) ? "" : "\n";

        $output = [];

        foreach ($this->moduleMatrix as $row) {
            $r = "";
            foreach ($row as $col) $r .= $col ?? "X";
            $output[] = $r;
        }

        return implode($newlines, $output);
    }

    /**
     * Outputs the QR code in its two-dimensional array form
     *
     * @return array
     */
    public function outputArray(): array
    {
        return $this->moduleMatrix;
    }

    /**
     * Renders QR code into an HTML table
     *
     * @param int $modulePixelSize
     * @return string
     */
    public function outputTable(int $modulePixelSize = 15): string
    {
        $borderSize = $modulePixelSize * 4;
        $output = "
            <style>
                table.qr-code {
                    margin: {$borderSize}px;
                    border: none;
                    -webkit-box-shadow:0px 0px 0px {$borderSize}px white;
                    -moz-box-shadow: 0px 0px 0px {$borderSize}px white;
                    box-shadow: 0px 0px 0px {$borderSize}px white;
                }
                table.qr-code tr, table.qr-code td { border: none; }
                table.qr-code tr { height: {$modulePixelSize}px; }
                table.qr-code td {
                    width: {$modulePixelSize}px;
                    height: {$modulePixelSize}px;
                    padding: 0; background-color: gray;
                }
                table.qr-code td.one { background-color: black; }
                table.qr-code td.zero { background-color: white; }
                table.qr-code td.two { background-color: blue; }
            </style>\n";

        $output .= "<table class='qr-code'>\n";

        for ($r = 0; $r < count($this->moduleMatrix); $r++) {
            $output .= "<tr>";
            for ($c = 0; $c < count($this->moduleMatrix[$r]); $c++) {
                $class = match ($this->moduleMatrix[$r][$c]) {
                    0 => "zero",
                    1 => "one",
                    2 => "two",
                    default => "null"
                };

                $output .= "<td class='$class'></td>";
            }
            $output .= "</tr>\n";
        }

        $output .= "</table>";

        return $output;
    }

    /**
     * Renders QR code into colored ASCII 219 blocks
     *
     * @param string $foreground
     * @param string $background
     * @return string
     */
    public function outputAscii(string $foreground = "000", string $background = "FFF"): string
    {
        $foreground = str_pad($foreground, 3, "0", STR_PAD_LEFT);
        $background = str_pad($background, 3, "0", STR_PAD_LEFT);
        $block = "██";

        $output = "
            <style>
                div.qr-code { font-family: monospace; }
                div.qr-code span { background-color: gray; color: gray; }
                div.qr-code .foreground { background-color: #$foreground; color: #$foreground; }
                div.qr-code .background { background-color: #$background; color: #$background; }
            </style>
            <div class='qr-code'>";

        for ($r = 0; $r < count($this->moduleMatrix); $r++) {
            $row = "";
            for ($c = 0; $c < count($this->moduleMatrix[$r]); $c++) {
                $class = match ($this->moduleMatrix[$r][$c]) {
                    0 => "background",
                    1 => "foreground",
                    default => "null"
                };

                $row .= "<span class='$class'>$block</span>";
            }
            $output .= "$row<br>\n";
        }
        $output .= "</div>\n";

        return $output;
    }

    // TODO: will render output using unicode 2800-28FF characters
    public function outputBraille(): void
    {

    }

    // TODO: will render a raster image
    public function outputRaster(): void
    {

    }

    // TODO: will render a vector image
    public function outputVector(): void
    {

    }
}