<?php

// https://www.ijcaonline.org/archives/volume156/number1/twum-2016-ijca-912342.pdf
// page 27-28 I think is worth reviewing to ensure this is working properly, however
// the tables seem to be... off?

// https://codyplanteen.com/assets/rs/gf256_log_antilog.pdf
// this has the GF256 tables

// https://dev.to/maxart2501/let-s-develop-a-qr-code-generator-part-iii-error-correction-1kbm
// this has various math operations but implementing them seems to give incorrect results

// https://www.thonky.com/qr-code-tutorial/log-antilog-table
// these are allegedly the correct lookup table values

declare(strict_types=1);

namespace supergnaw\LuxiQR;

trait GaloisFieldTrait
{
    /**
     * Generates the log/exp lookup tables for Galois Field 256 arithmetic
     * TODO: delete this because it's not used
     *
     * @return void
     */
    protected function generateLookupTables(): void
    {
//        $this->logTable = [
//            null, 0, 1, 25, 2, 50, 26, 198, 3, 223, 51, 238, 27, 104, 199, 75,
//            4, 100, 224, 14, 52, 141, 239, 129, 28, 193, 105, 248, 200, 8, 76, 113,
//            5, 138, 101, 47, 225, 36, 15, 33, 53, 147, 142, 218, 240, 18, 130, 69,
//            29, 181, 194, 125, 106, 39, 249, 185, 201, 154, 9, 120, 77, 228, 114, 166,
//            6, 191, 139, 98, 102, 221, 48, 253, 226, 152, 37, 179, 16, 145, 34, 136,
//            54, 208, 148, 206, 143, 150, 219, 189, 241, 210, 19, 92, 131, 56, 70, 64,
//            30, 66, 182, 163, 195, 72, 126, 110, 107, 58, 40, 84, 250, 133, 186, 61,
//            202, 94, 155, 159, 10, 21, 121, 43, 78, 212, 229, 172, 115, 243, 167, 87,
//            7, 112, 192, 247, 140, 128, 99, 13, 103, 74, 222, 237, 49, 197, 254, 24,
//            227, 165, 153, 119, 38, 184, 180, 124, 17, 68, 146, 217, 35, 32, 137, 46,
//            55, 63, 209, 91, 149, 188, 207, 205, 144, 135, 151, 178, 220, 252, 190, 97,
//            242, 86, 211, 171, 20, 42, 93, 158, 132, 60, 57, 83, 71, 109, 65, 162,
//            31, 45, 67, 216, 183, 123, 164, 118, 196, 23, 73, 236, 127, 12, 111, 246,
//            108, 161, 59, 82, 41, 157, 85, 170, 251, 96, 134, 177, 187, 204, 62, 90,
//            203, 89, 95, 176, 156, 169, 160, 81, 11, 245, 22, 235, 122, 117, 44, 215,
//            79, 174, 213, 233, 230, 231, 173, 232, 116, 214, 244, 234, 168, 80, 88, 175,
//        ];
//
//        $this->expTable = [
//            1, 2, 4, 8, 16, 32, 64, 128, 29, 58, 116, 232, 205, 135, 19, 38,
//            76, 152, 45, 90, 180, 117, 234, 201, 143, 3, 6, 12, 24, 48, 96, 192,
//            157, 39, 78, 156, 37, 74, 148, 53, 106, 212, 181, 119, 238, 193, 159, 35,
//            70, 140, 5, 10, 20, 40, 80, 160, 93, 186, 105, 210, 185, 111, 222, 161,
//            95, 190, 97, 194, 153, 47, 94, 188, 101, 202, 137, 15, 30, 60, 120, 240,
//            253, 231, 211, 187, 107, 214, 177, 127, 254, 225, 223, 163, 91, 182, 113, 226,
//            217, 175, 67, 134, 17, 34, 68, 136, 13, 26, 52, 104, 208, 189, 103, 206,
//            129, 31, 62, 124, 248, 237, 199, 147, 59, 118, 236, 197, 151, 51, 102, 204,
//            133, 23, 46, 92, 184, 109, 218, 169, 79, 158, 33, 66, 132, 21, 42, 84,
//            168, 77, 154, 41, 82, 164, 85, 170, 73, 146, 57, 114, 228, 213, 183, 115,
//            230, 209, 191, 99, 198, 145, 63, 126, 252, 229, 215, 179, 123, 246, 241, 255,
//            227, 219, 171, 75, 150, 49, 98, 196, 149, 55, 110, 220, 165, 87, 174, 65,
//            130, 25, 50, 100, 200, 141, 7, 14, 28, 56, 112, 224, 221, 167, 83, 166,
//            81, 162, 89, 178, 121, 242, 249, 239, 195, 155, 43, 86, 172, 69, 138, 9,
//            18, 36, 72, 144, 61, 122, 244, 245, 247, 243, 251, 235, 203, 139, 11, 22,
//            44, 88, 176, 125, 250, 233, 207, 131, 27, 54, 108, 216, 173, 71, 142, 1,
//        ];

        /*
         * I cheated
         * https://dev.to/maxart2501/let-s-develop-a-qr-code-generator-part-iii-error-correction-1kbm
         */

        /*
         * Cheating didn't work, and the math seems to sometimes work and sometimes not work and I don't know if it's the lookup tables or the math, and I'm losing hair
         */
//        $logTable = array_fill(start_index: 0, count: 256, value: 0);
//        $expTable = array_fill(start_index: 0, count: 256, value: 0);
//        $val = 1;
//        for ($e = 1; $e < 256; $e++) {
//            $val = $val > 127 ? (($val << 1) ^ 285) : $val << 1;
//            $expTable[$e % 255] = $val;
//            $logTable[$val] = $e % 255;
//        }
//
//        if ($logTable == $this->logTable) {
//            var_dump("log table is generated correctly");
//        }
//
//        if ($expTable == $this->expTable) {
//            var_dump("exp table is generated correctly");
//        } else {
//            var_dump($expTable);
//            var_dump($expTable);
//        }
//        $this->logTable = $logTable;
//        $this->expTable = $expTable;
    }

    /**
     * Multiplies two numbers using GF(256)
     *
     * @param int $a
     * @param int $b
     * @return int
     */
    protected function galoisFieldMultiply(int $a, int $b): int
    {
        if (0 === $a || 0 === $b) return 0;

        $log = self::LOG_TABLE[$a] + self::LOG_TABLE[$b];

        return self::EXP_TABLE[$log % 255];
    }

    /**
     * Multiplies two numbers using GF(256) using manual bitwise operations and no lookup tables
     *
     * @param int $a
     * @param int $b
     * @return int
     */
    protected function galoisFieldMultiplyManual(int $a, int $b): int
    {
        // made with chatgpt but individual results appear to be correct? I don't actually know what's happening here

        $p = 0; // Initialize result

        for ($i = 0; $i < 8; $i++) {
            // If the lowest bit of b is 1, XOR a value into result
            if ($b & 1) $p ^= $a;

            $b >>= 1; // Shift b to the right by 1

            // Check if a's the highest bit (bit 7) is set before shifting
            $carry = ($a & 0x80) ? 1 : 0;
            $a <<= 1; // Shift a's value to the left by 1

            // If carry was set, reduce using irreducible polynomial 0x11D
            if ($carry) $a ^= 0x11D;
        }

        return $p & 0xFF; // Ensure result is within GF(256)
    }

    /**
     * Divides two numbers using GF(256)
     *
     * @param int $n
     * @param int $d
     * @return int
     */
    protected function galoisFieldDivide(int $n, int $d): int
    {
        if ($d == 0) throw new LuxiQRException("Division by zero causes the universe to implode");

        if ($n == 0) return 0; // 0 divided by anything is still 0

        // GF 256 division is multiplication by the inverse
        return $this->galoisFieldMultiply($n, $this->galoisFieldInverse($d));
    }

    /**
     * Divides two numbers using GF(256) using manual bitwise operations and no lookup tables
     * @param int $n
     * @param int $d
     * @return int
     */
    protected function galoisFieldDivideManual(int $n, int $d): int
    {
        if ($d == 0) throw new LuxiQRException("Division by zero causes the universe to implode");

        if ($n == 0) return 0; // 0 divided by anything is still 0

        return $this->galoisFieldMultiplyManual($n, $this->galoisFieldInverse($d));
    }

    /**
     * Gets the multiplicative inverse of x
     *
     * @param int $x
     * @return int
     */
    protected function galoisFieldInverse(int $x): int
    {
        if ($x == 0) throw new LuxiQRException("Zero has no multiplicative inverse in GF(256).");

        $inverse = 1;
        $power = 254;

        // TODO: I don't know that this is working correctly
        while ($power > 0) {
            if ($power & 1) $inverse = $this->galoisFieldMultiply($inverse, $x);

            $x = $this->galoisFieldMultiply($x, $x);

            $power >>= 1;
        }

        return $inverse;
    }

    // original attempt, gives same answers as V2
    protected function galoisFieldInverseV1(int $x): int
    {
        if ($x == 0) throw new LuxiQRException("Zero has no multiplicative inverse in GF(256).");

        $inverse = 1;

        for ($i = 0; $i < 254; $i++) $inverse = $this->galoisFieldMultiply($inverse, $x);

        return $inverse;
    }

    /**
     * Multiplies two polynomials in GF(256)
     *
     * @param array $polyA
     * @param array $polyB
     * @return array
     */
    protected function multiplyPolynomials(array $polyA, array $polyB): array
    {
        $degreeA = count($polyA);
        $degreeB = count($polyB);
        $resultDegree = $degreeA + $degreeB - 1;
        $result = array_fill(0, $resultDegree, 0);

        for ($i = 0; $i < $degreeA; $i++) {
            for ($j = 0; $j < $degreeB; $j++) {
                $x = $i + $j;
                $a = $this->galoisFieldMultiply($polyA[$i], $polyB[$j]);
                $r = $result[$x] ^ $a;
                $result[$x] = $r;
            }
        }

        return $result;
    }


    /**
     * Divides two polynomials
     *
     * @param array $dividend
     * @param array $divisor
     * @param bool $remainderOnly
     * @return array
     */
    protected function dividePolynomials(array $dividend, array $divisor, bool $remainderOnly = true): array
    {
        $quotient = array_merge($dividend, array_fill(0, count($divisor), 0));

        for ($i = 0; $i < count($dividend); $i++) {
            if (0 == $quotient[$i]) continue;

            foreach ($divisor as $j => $coefficient) {
                $product = $this->galoisFieldMultiply($dividend[$i], $coefficient);
                // from my reading, I think XORing the product with the current coefficient is the same as division???
                $quotient[$i + $j] ^= $product;
            }
        }

        return ($remainderOnly)
            ? array_slice($quotient, -count($divisor))
            : [$quotient, array_slice($quotient, -count($divisor))];
    }

    // gives the same answers as V3
    protected function dividePolynomialsV2(array $dividend, array $divisor, bool $remainderOnly = true): array
    {
        $quotient = array_merge($dividend, $divisor);

        for ($c = 0; $c < count($quotient); $c++) {
            $coefficient = $quotient[$c];

            if (0 == $coefficient) continue;

            foreach ($divisor as $d => $dCoefficient) {
                $quotient[$c + $d] ^= $this->galoisFieldMultiply($coefficient, $dCoefficient);
            }
        }

        return ($remainderOnly) ? array_slice($quotient, -count($divisor)) : $quotient;
    }

    // gives the same answers as V2
    protected function dividePolynomialsV1(array $dividend, array $divisor): array|string
    {
        $quotientLength = count($dividend) - count($divisor) + 1;

        $rest = [$dividend];

        for ($count = 0; $count < $quotientLength; $count++) {
            // If the first term is 0, we can just skip this iteration
            if (0 < $rest[0]) {
                $factor = $this->dividePolynomials($rest[0], $divisor[0]);
                $intermediate = array_fill(0, count($rest), 0);

                $product = $this->multiplyPolynomials($divisor, [$factor]);
                for ($i = 0; $i < count($product); $i++) {
                    $intermediate[$i] = $product[$i];
                }

                $rest = array_slice(array_map(function ($value, $index) use ($intermediate) {
                    return $value ^ $intermediate[$index];
                }, $rest, array_keys($rest)), 1);
            } else {
                $rest = $rest[0];
            }
        }
        return $rest;
    }

    /**
     * Gets a generator polynomial for a given degree
     *
     * @param $degree
     * @return array|int[]
     */
    protected function getGeneratorPolynomial($degree): array
    {
        // this gives wildly different answers compared to the thonky.com generator tool
        $lastPoly = [1];
        for ($d = 0; $d < $degree; $d++) {
            $lastPoly = $this->multiplyPolynomials($lastPoly, [1, self::EXP_TABLE[$d % 255]]);
        }
        return $lastPoly;
    }

    // gives same answer as V2, because it's literally the same code...
    protected function getGeneratorPolynomialV1(int $degree): array
    {
        $generator = [1]; // Start with g(x) = 1

        for ($d = 0; $d < $degree; $d++) {
            // Create the term (x - α^i)
            $term = [1, self::EXP_TABLE[$d]]; // [1, α^$d]

            // Multiply the current generator polynomial by the new term
            $generator = $this->multiplyPolynomials($generator, $term);
        }

        return $generator; // Final generator polynomial
    }
}