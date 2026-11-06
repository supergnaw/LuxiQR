<?php

declare(strict_types=1);

use supergnaw\LuxiQR\LuxiQR;

require_once './vendor/autoload.php';

//$ssid = "HYRULE";
//$encryption = "WPA";
//$password = "Iambatman";
//$qrCode = LuxiQR::WiFi(ssid: $ssid, encryption: $encryption, password: $password);
//echo $qrCode->outputTable();

//$countryCode = 1;
//$phoneNumber = "(816) 551-8408";
//$qrCode = LuxiQR::Call(countryCode: $countryCode, phoneNumber: $phoneNumber);
//echo $qrCode->outputTable();

//$youtube = "https://www.youtube.com/watch?v=jNQXAC9IVRw";
//$qrCode = LuxiQR::YouTube($youtube);
//echo $qrCode->outputTable();

// TODO: this doesn't work
//$givenName = "First";
//$surName = "Last";
////$phone = ["(816) 867-5309" => "mobile"];
//$phone = ["+18168675309" => "mobile"];
//$qrCode = LuxiQR::VCard3(givenName: $givenName, surName: $surName, phone: $phone);
//echo $qrCode->outputTable();

$foreground = "#555";
$background = "#EEE";

$email = "example@example.com";
$subject = "subject line";
$body = "this is a test email";
$qrCode = LuxiQR::Email(email: $email, subject: $subject, body: $body);
$qrCode = new LuxiQR("Hello World!");
//echo $qrCode->outputTable(foreground: $foreground, background: $background);
//echo $qrCode->outputAscii(foreground: $foreground, background: $background);
echo $qrCode->outputPNG(foreground: $foreground, background: $background, moduleSize: 16);

$qrCode->

