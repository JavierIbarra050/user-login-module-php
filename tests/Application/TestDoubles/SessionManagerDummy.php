<?php

namespace UserLoginService\Tests\Application\TestDoubles;

use Exception;
use UserLoginService\Application\SessionManager;

class SessionManagerDummy implements SessionManager
{


    public function getSessions(): int
    {
        throw new Exception("Not implemented yet");
    }

    public function login(string $userName, string $password): bool
    {
        throw new Exception("Not implemented yet");
    }

    public function logout(string $userName): void
    {
        throw new Exception("Not implemented yet");
    }
}