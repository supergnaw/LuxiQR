<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR\factoryMethods;

use supergnaw\LuxiQR\exception\LuxiQRException;
use supergnaw\LuxiQR\LuxiQR;

trait WiFi
{
    /**
     * Formats a message for WiFi configuration
     *
     * @param string $ssid
     * @param string $encryption
     * @param string $password
     * @param bool $hidden
     * @param string $eccLevel
     * @return LuxiQR
     */
    public static function WiFi(
        string $ssid,
        string $encryption = "",
        string $password = "",
        bool   $hidden = false,
        string $eccLevel = "H"
    ): LuxiQR
    {
        $ssid = self::escapeString($ssid);
        $encryption = self::escapeString($encryption);
        $password = self::escapeString($password);
        $hidden = ($hidden) ? "true" : "false";

        if (empty($ssid)) {
            throw new LuxiQRException("SSID cannot be empty");
        }

        if (!in_array($encryption, ["WEP", "WPA", ""])) {
            throw new LuxiQRException("Invalid encryption type: $encryption");
        }

        if (empty($encryption) !== empty($password)) {
            throw new LuxiQRException("Encryption type and password must both be set or both be empty");
        }

        $wifi = "WIFI:S:$ssid;T:$encryption;P:$password;H:$hidden";

        return new LuxiQR(data: $wifi, eccLevel: $eccLevel);
    }

    private static function escapeString(string $string): string
    {
        return str_replace(
            [";", ",", ":", "\\"],
            ["\\;", "\\,", "\\:", "\\\\"],
            $string
        );
    }

}