<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR;

class LuxiQRException extends \RuntimeException
{
    /**
     * Throws an exception
     *
     * @param string $message
     * @param int $code
     * @param $previous
     */
    function __construct(string $message = "", int $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}