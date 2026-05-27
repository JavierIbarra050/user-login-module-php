<?php

namespace UserLoginService\Application;

use Exception;
use UserLoginService\Domain\User;

class UserLoginService
{
    private array $loggedUsers = [];

    public function __construct(private SessionManager $sessionManager) {}

    public function manualLogin(User $user): void
    {
        if(in_array($user, $this->loggedUsers)) { throw new Exception("User already logged in"); }

        $this->loggedUsers[] = $user;
    }

    public function login(string $userName, string $password): string
    {
        if($this->sessionManager->login($userName, $password))
        {
            $newUser = new User($userName);

            $this->loggedUsers[] = $newUser;

            return "Login correcto";
        }

        return "Login incorrecto";
    }

    public function logout(User $user): string
    {
        if(in_array($user, $this->loggedUsers))
        {
            $indice = array_search($user, $this->loggedUsers);
            unset($this->loggedUsers[$indice]);

            $this->sessionManager->logout($user->getUserName());

            return "ok";
        }

        return "User not found";
    }

    public function loggedUsers(): array
    { return $this->loggedUsers; }

    public function getExternalSessions(): int
    { return $this->sessionManager->getSessions(); }
}