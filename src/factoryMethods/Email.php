<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR\factoryMethods;

use supergnaw\LuxiQR\LuxiQR;

trait Email
{

    /**
     * Formats a message to send an email
     *
     * @param string $email
     * @param string|null $subject
     * @param string|null $body
     * @return LuxiQR
     */
    public static function Email(string $email, string $subject = null, string $body = null): LuxiQR
    {
        $input = array_filter([
            "subject" => trim(urlencode($subject)),
            "body" => trim(urlencode($body)),
        ]);

        $email = "mailto:" . trim($email) . ((!empty($input))
            ? "?" . implode("&", $input) : "");

        return new LuxiQR($email);
    }
}