<?php
namespace Grimzy\JWTServiceProvider\Exceptions;

use Exception;

class TokenException extends \UnexpectedValueException
{
    public function __construct(Exception $previous)
    {
        parent::__construct($previous->getMessage(), $previous->getCode(), $previous);
    }
}
