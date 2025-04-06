<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR\constants;

class Modes
{

    public const NUMERIC = "0001";

    public const ALPHANUMERIC = "0010";

    public const BYTE = "0100";

    public const KANJI = "1000";

    // TODO: this mode exists for extended character support but I do not know how to implement it
    public const ECI = "0111";

}