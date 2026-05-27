<?php

namespace UserLoginService\Tests\Application\TestDoubles;

use UserLoginService\Application\SessionManager;

class SessionManagerStub implements SessionManager
{

    public function getSessions(): int
    {
        return 2;
    }

    public function login(string $userName, string $password): bool
    {
        return true;
    }

    public function logout(string $userName): void
    { }
}