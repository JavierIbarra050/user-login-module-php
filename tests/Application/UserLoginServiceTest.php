<?php

declare(strict_types=1);

namespace UserLoginService\Tests\Application;

use PHPUnit\Framework\TestCase;
use UserLoginService\Application\SessionManager;
use UserLoginService\Application\UserLoginService;
use UserLoginService\Domain\User;

final class UserLoginServiceTest extends TestCase
{
    /**
     * @test
     */
    public function givenLoginOfUserWhoIsNotLoggedInReturnsArrayWithUser()
    {
        $sessionManagerDummy = $this->createMock(SessionManager::class);
        $userLoginService = new UserLoginService($sessionManagerDummy);

        $user = new User("username");
        $userLoginService->manualLogin($user);

        $this->assertEquals([$user], $userLoginService->loggedUsers());
    }

    /**
     * @test
     * @throws /Exception
     */
    public function givenUserWhoIsAlreadyLoggedInReturnsErrorException()
    {
        $sessionManagerDummy = $this->createMock(SessionManager::class);
        $userLoginService = new UserLoginService($sessionManagerDummy);

        $user = new User("username");
        $userLoginService->manualLogin($user);

        $this->expectExceptionMessage("User already logged in");
        $userLoginService->manualLogin($user);
    }

    /**
     * @test
     */
    public function externalSessionsWorksRight(){
        $sessionManagerStub = $this->createMock(SessionManager::class);
        $sessionManagerStub->method('getSessions')->willReturn(2);

        $userLoginService = new UserLoginService($sessionManagerStub);
        $result = $userLoginService->getExternalSessions();

        $this->assertEquals(2, $result);

    }
}
