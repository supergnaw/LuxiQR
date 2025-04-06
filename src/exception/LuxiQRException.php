<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR\exception;

use RuntimeException;

// TODO: split up and create new and exciting exceptions just to be super-cool

/*
 * When making more exceptions, of course just to be cool, I need to ensure I extend the appropriate
 * class, rather than extending the runtime exception, as applicable:
 *
 * https://www.php.net/manual/en/spl.exceptions.php
 *
 *  LogicException (extends Exception)
 *      BadFunctionCallException
 *          BadMethodCallException
 *      DomainException
 *      InvalidArgumentException
 *      LengthException
 *      OutOfRangeException
 *  RuntimeException (extends Exception)
 *      OutOfBoundsException
 *      OverflowException
 *      RangeException
 *      UnderflowException
 *      UnexpectedValueException
 */

class LuxiQRException extends RuntimeException
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
