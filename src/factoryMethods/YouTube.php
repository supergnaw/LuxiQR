<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR\factoryMethods;

use supergnaw\LuxiQR\exception\LuxiQRException;
use supergnaw\LuxiQR\LuxiQR;

trait YouTube
{
    /**
     * Formats a message for a URL
     *
     * @param string $url
     * @param string $eccLevel
     * @return LuxiQR
     */
    public static function YouTube(string $url, string $eccLevel = "H"): LuxiQR
    {
        $url = trim($url);

        if (empty($url)) throw new LuxiQRException("URL cannot be empty");

        if (!preg_match("/[v&]=([^&$]*)/", $url, $matches)) {
            throw new LuxiQRException("Invalid YouTube URL");
        }

        $url = "https://youtu.be/$matches[1]";

        return new LuxiQR(data: $url, eccLevel: $eccLevel);
    }
}