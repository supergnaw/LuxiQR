<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR;

class LuxiQRTest extends LuxiQR
{
    use DebugTrait;

    const TEST_MESSAGE_TEMPLATE = "<h4 class='%s'>Test '%s' %s</h4>";

    public function __construct()
    {
        echo "
            <style>
                html { background-color: black; color: #ccc; }
                html, head, body, p, table, td, tr, div, span { font-family: 'Coda', sans-serif; }
                table { border: 1px solid darkcyan;  border-collapse: collapse; background-color: #0f2f2f }
                th, td { border: 1px solid darkcyan; padding: 5px; text-align: center; }
                th { color: gold; }
                .passed { color: greenyellow; }
                .failed { color: orangered; }
                .debug { margin: 5px; padding: 5px; font-family: monospace; }
            </style>
            <link href='https://fonts.googleapis.com/css?family=Coda' rel='stylesheet' type='text/css'>
            <h1>LuxiQR Tests</h1>\n";

        // encoding trait methods
        echo "<h2>Encoding</h2>\n";
        $this->testDetectEncodingMode();
        $this->testEncode();

        // galois field math
        echo "<hr><h2>Galois Field 256 Math</h2>\n";
        $this->testLookupTables();
        $this->testMultiply();
        $this->testDivide();
        $this->testMultiplyPolynomials();
        $this->testGeneratorPolynomial();

        // error correction trait methods
        echo "<hr><h2>Error Correction</h2>\n";
        $this->testSplitDataBlocks();
        $this->testGenerateECCBlocks();

        // output
        echo "<hr><h2>Output</h2>\n";
        $this->testOutput();
    }


    // ENCODING

    public function testDetectEncodingMode(): void
    {
        $overallPassed = true;

        $tests = [
            [
                "data" => "8675309",
                "expected" => self::NUMERIC
            ],
            [
                "data" => "HELLO WORLD",
                "expected" => self::ALPHANUMERIC
            ],
            [
                "data" => "茗荷",
                "expected" => self::KANJI
            ],
            [
                "data" => "Hello, world!",
                "expected" => self::BYTE
            ]
        ];

        $values = [
            self::NUMERIC => "numeric",
            self::ALPHANUMERIC => "alphanumeric",
            self::KANJI => "kanji",
            self::BYTE => "byte"
        ];

        $table = "<table><tr><th>Status</th><th>Data</th><th>Expected</th><th>Result</th></tr>";
        $messageTemplate = "
                            <tr>
                                <td class='%s'>%s</td>
                                <td>%s</td>
                                <td>%s</td>
                                <td>%s</td>
                            </tr>";

        foreach ($tests as $test) {
            $this->initialize(data: $test["data"]);
            $status = ($test["expected"] == $this->mode) ? "passed" : "failed";

            if ("failed" == $status) $overallPassed = false;

            $table .= sprintf(
                $messageTemplate,
                $status,
                $status,
                $test["data"],
                $test["expected"] . " / " . $values[$test['expected']],
                $this->mode . " / " . $values[$this->mode]
            );
        }
        $table .= "</table>\n";

        echo sprintf(
            self::TEST_MESSAGE_TEMPLATE,
            ($overallPassed)
                ? "passed" : "failed",
            "detectEncodingMode",
            ($overallPassed)
                ? "passed" : "failed"
        );

        echo $table;
    }

    public function testEncode(): void
    {
        $overallPassed = true;

        // test data used is from the thonky qr code tutorial
        $tests = [
            [
                "data" => "8675309",
                "expected" => "I don't know",
                "ecc" => self::EC_QUARTILE,
                "mode" => "numeric"
            ],
            [
                "data" => "HELLO WORLD",
                "expected" => "00100000010110110000101101111000110100010111001011011100010011010100001101000000111011000001000111101100",
                "ecc" => self::EC_QUARTILE,
                "mode" => "alphanumeric"
            ],
            [
                "data" => "HELLO WORLD",
                "expected" => "00100000010110110000101101111000110100010111001011011100010011010100001101000000111011000001000111101100000100011110110000010001",
                "ecc" => self::EC_MEDIUM,
                "mode" => "alphanumeric"
            ],
            [
                "data" => "茗荷",
                "expected" => "I don't know",
                "ecc" => self::EC_QUARTILE,
                "mode" => "kanji"
            ],
            [
                "data" => "Hello, world!",
                "expected" => "I don't know",
                "ecc" => self::EC_QUARTILE,
                "mode" => "byte"
            ]
        ];

        $table = "<table><tr><th>Status</th><th>Mode</th><th>ECC</th><th>Data</th><th>Expected</th><th>Result</th></tr>";
        $messageTemplate = "
                        <tr>
                            <td class='%s'>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td style='vertical-align: top; text-align: left;'>%s</td>
                            <td style='vertical-align: top; text-align: left;'>%s</td>
                        </tr>";

        foreach ($tests as $test) {
            try {
                $this->initialize(
                    data: $test["data"],
                    eccLevel: $test["ecc"],
                    mode: $test["mode"]
                );
                $status = ($this->encodedData == $test["expected"]) ? "passed" : "failed";
            } catch (\Exception $e) {
                $result = "Exception: " . $e->getMessage();
                $status = "failed";
            }

            if ("failed" == $status) $overallPassed = false;

            $table .= sprintf(
                $messageTemplate,
                $status,
                $status,
                $test["mode"],
                $test["ecc"],
                $test["data"],
                $this->debugBits($test["expected"]),
                (preg_match("/^[\s01]+$/", $this->encodedData))
                    ? $this->debugBits($this->encodedData) : $this->encodedData
            );
        }
        $table .= "</table>\n";

        echo sprintf(
            self::TEST_MESSAGE_TEMPLATE,
            ($overallPassed)
                ? "passed" : "failed",
            "encode",
            ($overallPassed)
                ? "passed" : "failed"
        );

        echo $table;
    }

    // GALOIS FIELD MATH

    public function testLookupTables(): void
    {
        $overallPassed = true;

        $expExpected = [
            1, 2, 4, 8, 16, 32, 64, 128, 29, 58, 116, 232, 205, 135, 19, 38,
            76, 152, 45, 90, 180, 117, 234, 201, 143, 3, 6, 12, 24, 48, 96, 192,
            157, 39, 78, 156, 37, 74, 148, 53, 106, 212, 181, 119, 238, 193, 159, 35,
            70, 140, 5, 10, 20, 40, 80, 160, 93, 186, 105, 210, 185, 111, 222, 161,
            95, 190, 97, 194, 153, 47, 94, 188, 101, 202, 137, 15, 30, 60, 120, 240,
            253, 231, 211, 187, 107, 214, 177, 127, 254, 225, 223, 163, 91, 182, 113, 226,
            217, 175, 67, 134, 17, 34, 68, 136, 13, 26, 52, 104, 208, 189, 103, 206,
            129, 31, 62, 124, 248, 237, 199, 147, 59, 118, 236, 197, 151, 51, 102, 204,
            133, 23, 46, 92, 184, 109, 218, 169, 79, 158, 33, 66, 132, 21, 42, 84,
            168, 77, 154, 41, 82, 164, 85, 170, 73, 146, 57, 114, 228, 213, 183, 115,
            230, 209, 191, 99, 198, 145, 63, 126, 252, 229, 215, 179, 123, 246, 241, 255,
            227, 219, 171, 75, 150, 49, 98, 196, 149, 55, 110, 220, 165, 87, 174, 65,
            130, 25, 50, 100, 200, 141, 7, 14, 28, 56, 112, 224, 221, 167, 83, 166,
            81, 162, 89, 178, 121, 242, 249, 239, 195, 155, 43, 86, 172, 69, 138, 9,
            18, 36, 72, 144, 61, 122, 244, 245, 247, 243, 251, 235, 203, 139, 11, 22,
            44, 88, 176, 125, 250, 233, 207, 131, 27, 54, 108, 216, 173, 71, 142, 1,
        ];

        $logExpected = [
            0, 0, 1, 25, 2, 50, 26, 198, 3, 223, 51, 238, 27, 104, 199, 75,
            4, 100, 224, 14, 52, 141, 239, 129, 28, 193, 105, 248, 200, 8, 76, 113,
            5, 138, 101, 47, 225, 36, 15, 33, 53, 147, 142, 218, 240, 18, 130, 69,
            29, 181, 194, 125, 106, 39, 249, 185, 201, 154, 9, 120, 77, 228, 114, 166,
            6, 191, 139, 98, 102, 221, 48, 253, 226, 152, 37, 179, 16, 145, 34, 136,
            54, 208, 148, 206, 143, 150, 219, 189, 241, 210, 19, 92, 131, 56, 70, 64,
            30, 66, 182, 163, 195, 72, 126, 110, 107, 58, 40, 84, 250, 133, 186, 61,
            202, 94, 155, 159, 10, 21, 121, 43, 78, 212, 229, 172, 115, 243, 167, 87,
            7, 112, 192, 247, 140, 128, 99, 13, 103, 74, 222, 237, 49, 197, 254, 24,
            227, 165, 153, 119, 38, 184, 180, 124, 17, 68, 146, 217, 35, 32, 137, 46,
            55, 63, 209, 91, 149, 188, 207, 205, 144, 135, 151, 178, 220, 252, 190, 97,
            242, 86, 211, 171, 20, 42, 93, 158, 132, 60, 57, 83, 71, 109, 65, 162,
            31, 45, 67, 216, 183, 123, 164, 118, 196, 23, 73, 236, 127, 12, 111, 246,
            108, 161, 59, 82, 41, 157, 85, 170, 251, 96, 134, 177, 187, 204, 62, 90,
            203, 89, 95, 176, 156, 169, 160, 81, 11, 245, 22, 235, 122, 117, 44, 215,
            79, 174, 213, 233, 230, 231, 173, 232, 116, 214, 244, 234, 168, 80, 88, 175,
        ];

        $expTable = "
            <table>
                <caption>Exponent Lookup Table</caption>
                <tr>
                    <th></th>
                    <th>_0</th>
                    <th>_1</th>
                    <th>_2</th>
                    <th>_3</th>
                    <th>_4</th>
                    <th>_5</th>
                    <th>_6</th>
                    <th>_7</th>
                    <th>_8</th>
                    <th>_9</th>
                    <th>_A</th>
                    <th>_B</th>
                    <th>_C</th>
                    <th>_D</th>
                    <th>_E</th>
                    <th>_F</th>
                </tr>";
        for ($i = 0; $i < 256; $i += 16) {
            $l = strtoupper(dechex(intdiv($i, 16)));
            $expTable .= "<tr><th>{$l}_</th>";
            for ($j = 0; $j < 16; $j++) {
                $k = $i + $j;
                $status = ($expExpected[$k] == self::EXP_TABLE[$k]) ? "passed" : "failed";

                if ("failed" == $status) $overallPassed = false;

                $expTable .= "<td><span class='$status'>{$expExpected[$k]}</span> / " . self::EXP_TABLE[$k] . "</td>";
            }
            $expTable .= "</tr>";
        }
        $expTable .= "</table>\n";


        $logTable = "
            <table>
                <caption>Log Lookup Table</caption>
                <tr>
                    <th></th>
                    <th>_0</th>
                    <th>_1</th>
                    <th>_2</th>
                    <th>_3</th>
                    <th>_4</th>
                    <th>_5</th>
                    <th>_6</th>
                    <th>_7</th>
                    <th>_8</th>
                    <th>_9</th>
                    <th>_A</th>
                    <th>_B</th>
                    <th>_C</th>
                    <th>_D</th>
                    <th>_E</th>
                    <th>_F</th>
                </tr>";
        for ($i = 0; $i < 256; $i += 16) {
            $l = strtoupper(dechex(intdiv($i, 16)));
            $logTable .= "<tr><th>{$l}_</th>";
            for ($j = 0; $j < 16; $j++) {
                $k = $i + $j;
                $status = ($logExpected[$k] == self::LOG_TABLE[$k]) ? "passed" : "failed";

                if ("failed" == $status) $overallPassed = false;

                $logTable .= "<td><span class='$status'>{$logExpected[$k]}</span> / " . self::LOG_TABLE[$k] . "</td>";
            }
            $logTable .= "</tr>";
        }
        $logTable .= "</table>\n";

        echo sprintf(
            self::TEST_MESSAGE_TEMPLATE,
            ($overallPassed)
                ? "passed" : "failed",
            "lookupTables",
            ($overallPassed)
                ? "passed" : "failed"
        );

        echo $expTable;

        echo $logTable;

    }

    public function testMultiply(): void
    {
        $overallPassed = true;

        $tests = [
            ["a" => 3, "b" => 4, "expected" => 12],
            ["a" => 99, "b" => 227, "expected" => 107],
            ["a" => 252, "b" => 228, "expected" => 47],
            ["a" => 182, "b" => 39, "expected" => 102],
            ["a" => 56, "b" => 248, "expected" => 222],
            ["a" => 155, "b" => 63, "expected" => 133],
            ["a" => 3, "b" => 90, "expected" => 238],
            ["a" => 0, "b" => 1, "expected" => 0],
            ["a" => 255, "b" => 255, "expected" => 226],
            ["a" => 87, "b" => 131, "expected" => 226],
            ["a" => 240, "b" => 170, "expected" => 226],
//            ["a" => 15, "b" => 5, "expected" => 75],
//            ["a" => 120, "b" => 45, "expected" => 209],
        ];

        $table = "<table><tr><th>Status</th><th>A</th><th>B</th><th>Log(A)</th><th>Log(B)</th><th>Sum</th><th>Expected</th><th>Result</th></tr>";
        $messageTemplate = "
            <tr>
                <td class='%s'>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
            </tr>";

        foreach ($tests as $test) {
            $expected = $test["expected"];
            $result = $this->galoisFieldMultiply($test["a"], $test["b"]);
            $status = ($expected === $result) ? "passed" : "failed";

            if ("failed" == $status) $overallPassed = false;

            $table .= sprintf(
                $messageTemplate,
                $status,
                $status,
                $test["a"],
                $test["b"],
                self::LOG_TABLE[$test["a"]],
                self::LOG_TABLE[$test["b"]],
                self::LOG_TABLE[$test["a"]] + self::LOG_TABLE[$test["b"]],
                $expected,
                $result);
        }
        $table .= "</table>\n";

        echo sprintf(
            self::TEST_MESSAGE_TEMPLATE,
            ($overallPassed)
                ? "passed" : "failed",
            "galoisFieldMultiply",
            ($overallPassed)
                ? "passed" : "failed"
        );

        echo $table;
    }

    public function testDivide(): void
    {
        $overallPassed = true;

        $tests = [
            ["a" => 15, "b" => 5, "expected" => 166],
            ["a" => 120, "b" => 45, "expected" => 69],
            ["a" => 200, "b" => 17, "expected" => 173]
        ];

        $table = "<table><tr><th>Status</th><th>A</th><th>B</th><th>Expected</th><th>Result</th></tr>";
        $messageTemplate = "<tr><td class='%s'>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";

        foreach ($tests as $test) {
            $expected = $test["expected"];
            $result = $this->galoisFieldDivide($test["a"], $test["b"]);
            $status = ($expected === $result) ? "passed" : "failed";
            if ("failed" == $status) $overallPassed = false;
            $table .= sprintf($messageTemplate, $status, $status, $test["a"], $test["b"], $expected, $result);
        }
        $table .= "</table>\n";

        echo sprintf(
            self::TEST_MESSAGE_TEMPLATE,
            ($overallPassed)
                ? "passed" : "failed",
            "galoisFieldDivide",
            ($overallPassed)
                ? "passed" : "failed"
        );

        echo $table;
    }

    public function testMultiplyPolynomials(): void
    {
        $overallPassed = true;

        $tests = [
            [
                "a" => [1, 2, 3],
                "b" => [4, 5],
                "expected" => [4, 13, 22, 15]
            ],
            [
                "a" => [2, 3],
                "b" => [4, 5],
                "expected" => [16, 112, 8]
            ],
            [
                "a" => [0, 0, 0, 0, 0, 0, 0, 46, 67, 251, 0],
                "b" => [0, 0, 0, 0, 70, 0, 118, 0, 67],
                // my powers of observation tell me this expected poly is 9 degrees short
                "expected" => [0, 0, 0, 0, 70, 0, 92, 67, 186, 0],
            ],
            [
                "a" => [1, 2],
                "b" => [1, 2],
                "expected" => [1, 25, 0]
            ],
            [
                "a" => [3, 3],
                "b" => [3, 3],
                "expected" => ["who", "fucking", "knows"]
            ]
        ];

        $table = "<table><tr><th>Status</th><th>A</th><th>B</th><th>Expected</th><th>Result</th></tr>";
        $messageTemplate = "<tr><td class='%s'>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";

        foreach ($tests as $test) {
            $expected = $test["expected"];
            $result = $this->multiplyPolynomials($test["a"], $test["b"]);
            $status = ($expected === $result) ? "passed" : "failed";
            if ("failed" == $status) $overallPassed = false;
            $table .= sprintf($messageTemplate, $status, $status, $this->arr2Str($test["a"]), $this->arr2Str($test["b"]), $this->arr2Str($expected), $this->arr2Str($result));
        }
        $table .= "</table>\n";

        echo sprintf(
            self::TEST_MESSAGE_TEMPLATE,
            ($overallPassed)
                ? "passed" : "failed",
            "multiplyPolynomials",
            ($overallPassed)
                ? "passed" : "failed"
        );

        echo $table;
    }

    public function testGeneratorPolynomial(): void
    {
        $overallPassed = true;

        $tests = [
            ["d" => 7, "expected" => [0, 87, 229, 146, 149, 238, 102, 21]],
            ["d" => 10, "expected" => [0, 251, 67, 46, 61, 118, 70, 64, 94, 32, 45]],
            ["d" => 16, "expected" => [0, 120, 104, 107, 109, 102, 161, 76, 3, 91, 191, 147, 169, 182, 194, 225, 120]],
            ["d" => 32, "expected" => [
                0, 10, 6, 106, 190, 249, 167, 4, 67, 209, 138, 138, 32, 242, 123, 89, 27,
                120, 185, 80, 156, 38, 69, 171, 60, 28, 222, 80, 52, 254, 185, 220, 241
            ]],
        ];

        $table = "<table><tr><th>Status</th><th>Degree</th><th>Expected</th><th>Result</th></tr>";
        $messageTemplate = "<tr><td class='%s'>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";

        foreach ($tests as $test) {
            $expected = $test["expected"];
            $result = $this->getGeneratorPolynomial($test["d"]);
            $status = ($expected === $result) ? "passed" : "failed";
            if ("failed" == $status) $overallPassed = false;
            $table .= sprintf($messageTemplate, $status, $status, $test["d"], $this->arr2Str($expected), $this->arr2Str($result));
        }
        $table .= "</table>\n";

        echo ($overallPassed)
            ? sprintf(self::TEST_MESSAGE_TEMPLATE, "passed", "generatorPolynomial", "passed")
            : sprintf(self::TEST_MESSAGE_TEMPLATE, "failed", "generatorPolynomial", "failed");

        echo $table;
    }


    // ERROR CORRECTION TRAITS

    public function testSplitDataBlocks(): void
    {
        $overallPassed = true;

        $tests = [
            [
                "data" => "8675309",
                "ecc" => self::EC_LOW,
                "expected" => [19, 0, 0, 0]
            ],
            [
                "data" => "HELLO WORLD",
                "ecc" => self::EC_MEDIUM,
                "expected" => [16, 0, 0, 0]
            ],
            [
                "data" => "Hello, world!",
                "ecc" => self::EC_QUARTILE,
                "expected" => [22, 0, 0, 0]
            ],
            [
                "data" => "There's a frood who really knows where his towel is.",
                "ecc" => self::EC_QUARTILE,
                "expected" => [15, 15, 16, 16]
            ],
            [
                "data" => "This Pangram contains four a’s, one b, two c’s, one d, thirty e’s, six f’s, five g’s, seven h’s, eleven i’s, one j, one k, two l’s, two m’s, eighteen n’s, fifteen o’s, two p’s, one q, five r’s, twenty-seven s’s, eighteen t’s, two u’s, seven v’s, eight w’s, two x’s, three y’s, & one z.",
                "ecc" => self::EC_QUARTILE,
                "expected" => [22, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23]
            ]
        ];

        $table = "<table><tr><th>Status</th><th>Data</th><th>V-ECC</th><th>Expected</th><th>Result</th></tr>";
        $messageTemplate = "<tr><td class='%s'>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";

        foreach ($tests as $test) {
            $this->initialize(
                data: $test["data"],
                eccLevel: $test["ecc"]
            );

            $result = [0, 0, 0, 0];

            for ($b = 0; $b < count($this->dataBlocks); $b++) {
                $result[$b] = count($this->dataBlocks[$b]);
            }

            $status = ($test["expected"] == $result) ? "passed" : "failed";

            if ("failed" == $status) $overallPassed = false;

            $table .= sprintf(
                $messageTemplate,
                $status,
                $status,
                $test["data"],
                "{$this->version}-{$this->eccLevel}",
                $this->arr2Str($test["expected"]),
                $this->arr2Str($result)
            );
        }

        $table .= "</table>\n";

        echo sprintf(
            self::TEST_MESSAGE_TEMPLATE,
            ($overallPassed)
                ? "passed" : "failed",
            "splitDataBlocks",
            ($overallPassed)
                ? "passed" : "failed"
        );

        echo $table;
    }

    public function testGenerateECCBlocks(): void
    {
        $overallPassed = true;

        $tests = [
            [
                "data" => "8675309",
                "ecc" => self::EC_LOW,
                "expected" => [7]
            ],
            [
                "data" => "HELLO WORLD",
                "ecc" => self::EC_MEDIUM,
                "expected" => [10]
            ],
            [
                "data" => "Hello, world!",
                "ecc" => self::EC_QUARTILE,
                "expected" => [22]
            ],
            [
                "data" => "There's a frood who really knows where his towel is.",
                "ecc" => self::EC_QUARTILE,
                "expected" => [18, 18, 18, 18]
            ],
            [
                "data" => "This Pangram contains four a’s, one b, two c’s, one d, thirty e’s, six f’s, five g’s, seven h’s, eleven i’s, one j, one k, two l’s, two m’s, eighteen n’s, fifteen o’s, two p’s, one q, five r’s, twenty-seven s’s, eighteen t’s, two u’s, seven v’s, eight w’s, two x’s, three y’s, & one z.",
                "ecc" => self::EC_QUARTILE,
                "expected" => [28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28]
            ]
        ];

        $table = "<table><tr><th>Status</th><th>Data</th><th>V-ECC</th><th>Expected</th><th>Result</th></tr>";
        $messageTemplate = "<tr><td class='%s'>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";

        foreach ($tests as $test) {
            $this->initialize(
                data: $test["data"],
                eccLevel: $test["ecc"]
            );
            $result = [];

            foreach ($this->eccBlocks as $e) $result[] = count($e);

            $status = ($test["expected"] == $result) ? "passed" : "failed";

            if ("failed" == $status) $overallPassed = false;

            $table .= sprintf(
                $messageTemplate,
                $status,
                $status,
                $test["data"],
                "{$this->version}-{$this->eccLevel}",
                $this->arr2Str($test["expected"]),
                $this->arr2Str($result)
            );
        }

        $table .= "</table>\n";

        echo sprintf(
            self::TEST_MESSAGE_TEMPLATE,
            ($overallPassed)
                ? "passed" : "failed",
            "generateEccBlocks",
            ($overallPassed)
                ? "passed" : "failed"
        );

        echo $table;
    }

    // OUTPUT

    public function testOutput(): void
    {
        // test data used is from the thonky qr code tutorial
        $tests = [
            [
                "data" => 8675309,
                "ecc" => "L",
                "output" => "raw"
            ],
            [
                "data" => "HELLO WORLD",
                "ecc" => "M",
                "output" => "array"
            ],
            [
                "data" => "Hello, world!",
                "ecc" => "Q",
                "output" => "table"
            ],
            [
                "data" => "There's a frood who really knows where his towel is.",
                "ecc" => "H",
                "output" => "raw"
            ],
            [
                "data" => "THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG",
                "ecc" => "Low",
                "output" => "array"
            ],
            [
                "data" => "This Pangram contains four a’s, one b, two c’s, one d, thirty e’s, six f’s, five g’s, seven h’s, eleven i’s, one j, one k, two l’s, two m’s, eighteen n’s, fifteen o’s, two p’s, one q, five r’s, twenty-seven s’s, eighteen t’s, two u’s, seven v’s, eight w’s, two x’s, three y’s, & one z.",
                "ecc" => "M",
                "output" => "table"
            ],
            [
                "data" =>
                    "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis consequat, ligula eget malesuada egestas, augue tortor efficitur tortor, ut rhoncus erat nisi vel metus. Donec tincidunt pharetra tellus, sed tincidunt nibh tempus aliquam. Proin lorem nulla, consequat porttitor massa quis, cursus molestie ante. Fusce ultrices lacinia sapien nec aliquam. Etiam rutrum laoreet tortor placerat viverra. Nam sodales ornare mattis. Pellentesque varius elit a tellus iaculis, a faucibus ante venenatis. Integer non dapibus nisi, id pharetra turpis. Ut et augue auctor elit feugiat laoreet sit amet et dolor. Proin sed turpis vitae quam vehicula fringilla bibendum sit amet ligula. Nullam condimentum dapibus dui ac sodales. In tincidunt nunc lacus, eu varius lectus semper nec. Nam rutrum sed neque ut vulputate." .
                    "Aliquam at lacus lacus. Nam bibendum lorem vitae varius dignissim. Duis posuere placerat mi, in dictum lacus. Aliquam erat volutpat. Suspendisse congue in ante quis venenatis. Curabitur eget diam sapien. Mauris ac ante facilisis, gravida tellus at, venenatis libero.",
                "ecc" => "H",
                "output" => "invalid"
            ]
        ];

        foreach ($tests as $test) {
            $this->initialize(
                data: $test["data"],
                eccLevel: $test["ecc"]
            );

            echo "<h4 class='%s'>Test output: {$test['output']}</h4>";
            echo "<h5 class='%s'>{$test['data']}</h5>";

            echo match ($test["output"]) {
                "raw" => "<pre>{$this->outputRawString()}</pre>",
                "array" => "<pre>{$this->arr2Str($this->outputArray())}</pre>",
                "table" => $this->outputTable(),
                "ascii" => $this->outputAscii(),
                default => "<p>No valid output mmethod chosen.</p>"
            };
        }
    }
}
