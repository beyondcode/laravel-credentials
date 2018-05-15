<?php

namespace BeyondCode\Credentials\Exceptions;

use Exception;

class InvalidJSON extends Exception
{
    public static function create($error)
    {
        return new static("Unable to parse credential JSON ".self::getErrorMessage($error));
    }

    private static function getErrorMessage($error)
    {
        switch ($error) {
            case JSON_ERROR_DEPTH:
                return ' - Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                return ' - Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                return ' - Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                return ' - Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                return ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                return ' - Unknown error';
                break;
        }
    }
}