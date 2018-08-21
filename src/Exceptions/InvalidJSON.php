<?php

namespace BeyondCode\Credentials\Exceptions;

use Exception;

class InvalidJSON extends Exception
{
    /**
     * Create a new InvalidJson exception instance.
     *
     * @param int $error
     * @return $this
     */
    public static function create($error)
    {
        return new static("Unable to parse credential JSON ".self::getErrorMessage($error));
    }

    /**
     * Get the error message.
     *
     * @param int $error
     * @return string
     */
    private static function getErrorMessage($error)
    {
        switch ($error) {
            case JSON_ERROR_DEPTH:
                return ' - Maximum stack depth exceeded';
            case JSON_ERROR_STATE_MISMATCH:
                return ' - Underflow or the modes mismatch';
            case JSON_ERROR_CTRL_CHAR:
                return ' - Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return ' - Syntax error, malformed JSON';
            case JSON_ERROR_UTF8:
                return ' - Malformed UTF-8 characters, possibly incorrectly encoded';
            default:
                return ' - Unknown error';
        }
    }
}
