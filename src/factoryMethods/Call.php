<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR\factoryMethods;

use supergnaw\LuxiQR\exception\LuxiQRException;
use supergnaw\LuxiQR\LuxiQR;

trait Call
{
    /**
     * Formats a message, and converts any phonewords, for a phone call
     *
     * @param int|string $countryCode
     * @param int|string $phoneNumber
     * @param string $eccLevel
     * @return LuxiQR
     */
    public static function Call(int|string $countryCode, int|string $phoneNumber, string $eccLevel = "H"): LuxiQR
    {
        $countryCode = preg_replace("/\D/", "", strval($countryCode));

        if (empty($countryCode)) throw new LuxiQRException("Country code cannot be empty");

        $phoneNumber = preg_replace("/\D/", "", Call::convertPhoneword(strval($phoneNumber)));

        if (empty($phoneNumber)) throw new LuxiQRException("Phone number cannot be empty");

        $call = "tel:+{$countryCode}{$phoneNumber}";

        return new LuxiQR(data: $call, eccLevel: $eccLevel);
    }

    public static function convertPhoneword(string $input): string
    {
        $output = "";

        foreach (str_split($input) as $char) {
            if (is_numeric($char)) {
                $output .= $char;
                continue;
            }

            $output .= match (strtoupper($char)) {
                "A", "B", "C" => 2,
                "D", "E", "F" => 3,
                "G", "H", "I" => 4,
                "J", "K", "L" => 5,
                "M", "N", "O" => 6,
                "P", "Q", "R", "S" => 7,
                "T", "U", "V" => 8,
                "W", "X", "Y", "Z" => 9,
                default => throw new LuxiQRException("Invalid character for phoneword: $char")
            };
        }

        return $output;
    }
}