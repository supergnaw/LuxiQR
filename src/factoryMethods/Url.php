<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR\factoryMethods;

use supergnaw\LuxiQR\exception\LuxiQRException;
use supergnaw\LuxiQR\LuxiQR;

trait Url
{
    /**
     * Formats a message for a URL
     *
     * @param string $url
     * @param string $eccLevel
     * @return LuxiQR
     */
    public static function Url(string $url, string $eccLevel = "H"): LuxiQR
    {
        $url = trim($url);

        if (empty($url)) throw new LuxiQRException("URL cannot be empty");

        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) $url = "https://" . $url;

        return new LuxiQR(data: $url, eccLevel: $eccLevel);
    }
}