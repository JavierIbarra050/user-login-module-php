<?php

namespace UserLoginService\Infrastructure\Exceptions;

use Exception;
class UserNotLoggedInException extends Exception
{

    public function __construct($message = "Usuario no loggeado")
    {
        parent::__construct($message);
    }
}