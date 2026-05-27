<?php

namespace UserLoginService\Infrastructure\Exceptions;

use Exception;
class ServiceNotAvailableException extends Exception
{

    public function __construct($message = "Servicio no disponible")
    {
        parent::__construct($message);
    }


}