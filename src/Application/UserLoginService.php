<?php

namespace UserLoginService\Application;

use Exception;
use UserLoginService\Domain\User;

class UserLoginService
{
    private array $loggedUsers = [];

    public function manualLogin(User $user): void
    {
        if(in_array($user, $this->loggedUsers)) { Throw new Exception("User already logged in"); }

        $this->loggedUsers[] = $user;
    }

    public function loggedUsers(): array
    {
        return $this->loggedUsers;
    }

}