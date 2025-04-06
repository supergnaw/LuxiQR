<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR\factoryMethods;

use supergnaw\LuxiQR\exception\LuxiQRException;
use supergnaw\LuxiQR\LuxiQR;

trait VCard3
{
    public static function VCard3(
        string       $prefix = null,
        string       $givenName = null,
        string       $middleName = null,
        string       $surName = null,
        string       $suffix = null,
        string       $title = null,
        string       $nickname = null,
        string       $photo = null,
        string       $email = null,
        string|array $tags = null,
        array        $phone = []
    ): LuxiQR
    {

        $vCard = [
            "BEGIN:VCARD",
            "VERSION:3.0",
            "N:" . implode(";", array_filter([
                $surName ?: "",
                $givenName ?: "",
                $middleName ?: "",
                $prefix ?: "",
                $suffix ?: ""
            ]))
        ];

        // Add the formatted name
        $formattedName = trim(implode(" ", array_filter(
            [$prefix, $givenName, $middleName, $surName, $suffix]
        )));
        if (!empty($formattedName)) $vCard[] = "FN:" . $formattedName;

        // Add other fields if they exist
        if (!empty($title)) $vCard[] = "TITLE:" . $title;
        if (!empty($nickname)) $vCard[] = "NICKNAME:" . $nickname;
        if (!empty($photo)) $vCard[] = "PHOTO:" . $photo;
        if (!empty($email)) $vCard[] = "EMAIL:" . $email;

        // Add phone numbers
        foreach ($phone as $number => $type) $vCard[] = self::processPhoneNumber($type, $number);

        // Add categories/tags
        if (!empty($tags)) {
            $vCard[] = "CATEGORIES:" . (is_array($tags) ? implode(",", $tags) : $tags);
        }

        // end card
        $vCard[] = "END:VCARD";

        var_dump($vCard);

        $vCard = implode("\r\n", $vCard) . "\r\n";

        var_dump($vCard);

        return new LuxiQR(data: $vCard, eccLevel: "H");
    }

    private static function processPhoneNumber(string $type, string $number): string
    {
        $type = match (strtoupper($type)) {
            "WORK", "HOME" => strtoupper($type),
            "CELL", "MOBILE" => "CELL",
            default => throw new LuxiQRException("Invalid telephone number type: $type")
        };

        if (preg_match("/[^\d\+\s\(\)\-]/", $number, $matches)) {
            throw new LuxiQRException("Telephone number contains invalid characters: $matches");
        } else {
            // TODO: add some international logic here one day
            $number = trim($number);
        }

        return "TEL;TYPE=$type:$number";
    }
}

/*

BEGIN:VCARD
VERSION:3.0
N:Last;First;;;
FN:First Last
TEL;TYPE=CELL:+18168675309
END:VCARD


BEGIN:VCARD
VERSION:3.0
N:Last;First;;;
FN:First Last
TEL;TYPE=CELL:+18168675309
END:VCARD

 */