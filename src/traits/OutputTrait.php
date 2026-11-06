<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR\traits;

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
    public function outputTable(int $modulePixelSize = 15, $foreground = "black", $background = "white", $debug = "blue"): string
    {
        $output = "
            <style>
                table.qr-code {
                    border: none;
                    border-collapse: collapse;
                }
                table.qr-code tr, table.qr-code td { border: none; }
                table.qr-code tr { height: {$modulePixelSize}px; }
                table.qr-code td {
                    width: {$modulePixelSize}px;
                    height: {$modulePixelSize}px;
                    padding: 0; background-color: gray;
                }
                table.qr-code td.one { background-color: $foreground; }
                table.qr-code td.zero { background-color: $background; }
                table.qr-code td.two { background-color: $debug; }
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
    public function outputAscii(string $foreground = "#000", string $background = "#FFF"): string
    {
        $foreground = str_pad($foreground, 3, "0", STR_PAD_LEFT);
        $background = str_pad($background, 3, "0", STR_PAD_LEFT);
        $block = "██";

        $output = "
            <style>
                div.qr-code { font-family: monospace; }
                div.qr-code span { background-color: gray; color: gray; }
                div.qr-code .foreground { background-color: $foreground; color: $foreground; }
                div.qr-code .background { background-color: $background; color: $background; }
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
        // this is going to be a pain: https://www.unicode.org/charts/nameslist/c_2800.html
        $charmap = [
            "00" => [
                "00" => [
                    "00" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "01" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "10" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "11" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ]
                ],
                "01" => [
                    "00" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "01" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "10" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "11" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ]
                ],
                "10" => [
                    "00" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "01" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "10" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "11" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ]
                ],
                "11" => [
                    "00" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "01" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "10" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "11" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ]
                ]
            ],
            "01" => [
                "00" => [
                    "00" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "01" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "10" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "11" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ]
                ],
                "01" => [
                    "00" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "01" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "10" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "11" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ]
                ],
                "10" => [
                    "00" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "01" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "10" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "11" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ]
                ],
                "11" => [
                    "00" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "01" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "10" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "11" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ]
                ]
            ],
            "10" => [
                "00" => [
                    "00" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "01" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "10" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "11" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ]
                ],
                "01" => [
                    "00" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "01" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "10" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "11" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ]
                ],
                "10" => [
                    "00" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "01" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "10" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "11" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ]
                ],
                "11" => [
                    "00" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "01" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "10" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "11" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ]
                ]
            ],
            "11" => [
                "00" => [
                    "00" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "01" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "10" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "11" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ]
                ],
                "01" => [
                    "00" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "01" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "10" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "11" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ]
                ],
                "10" => [
                    "00" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "01" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "10" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "11" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ]
                ],
                "11" => [
                    "00" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "01" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "10" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ],
                    "11" => [
                        "00" => "&#x2800",
                        "01" => "&#x2800",
                        "10" => "&#x2800",
                        "11" => "&#x2800"
                    ]
                ]
            ]
        ];
    }

    /**
     * Renders QR code as a PNG image
     *
     * @param string $foreground
     * @param string $background
     * @param int $moduleSize
     * @return void
     */
    public function outputPNG(string $foreground = "#000", string $background = "#FFF", int $moduleSize = 8): string
    {
        // create blank output canvas
        $canvas = imagecreatetruecolor(
            width: count($this->moduleMatrix) * $moduleSize,
height: count($this->moduleMatrix[0]) * $moduleSize,
        );

        $fg = self::hex2Rgb($foreground);
        $fg = imagecolorallocatealpha($canvas, $fg["r"], $fg["g"], $fg["b"], 0);
        $bg = self::hex2Rgb($background);
        $bg = imagecolorallocatealpha($canvas, $bg["r"], $bg["g"], $bg["b"], 0);

        imagefill($canvas, 0, 0, $bg);

        for ($y = 0; $y < count($this->moduleMatrix); $y++) {
            for ($x = 0; $x < count($this->moduleMatrix[0]); $x++) {
                if (0 == $this->moduleMatrix[$y][$x]) continue;

                imagefilledrectangle(
                    image: $canvas,
                    x1: $x * $moduleSize,
                    y1: $y * $moduleSize,
                    x2: $x * $moduleSize + $moduleSize - 1,
                    y2: $y * $moduleSize + $moduleSize - 1,
                    color: $fg
                );
            }
        }

        ob_start();
        imagepng($canvas);
        $imageBase64 = base64_encode(ob_get_clean());

        return "<img src='data:image/png;base64,$imageBase64' />";
    }

    // TODO: will render a JPG image
    public function outputJPG(): void
    {

    }

    // TODO: will render an SVG image
    public function outputSVG(): void
    {

    }

    protected static function hex2Rgb(string $hex): array
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];

        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }
}