<?php

declare(strict_types=1);

namespace UserLoginService\Tests\Application;

use Mockery;
use PHPUnit\Framework\TestCase;
use UserLoginService\Application\SessionManager;
use UserLoginService\Application\UserLoginService;
use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\Exceptions\ServiceNotAvailableException;
use UserLoginService\Infrastructure\Exceptions\UserNotLoggedInException;
use UserLoginService\Tests\Application\TestDoubles\SessionManagerDummy;
use UserLoginService\Tests\Application\TestDoubles\SessionManagerStub;

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
        $sessionManagerDummy = new SessionManagerDummy();
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
        $sessionManagerStub = new SessionManagerStub();

        $userLoginService = new UserLoginService($sessionManagerStub);
        $result = $userLoginService->getExternalSessions();

        $this->assertEquals(2, $result);
    }

    /**
     * @test
     */
    public function logoutFunctionWithExistingUserDeletesTheUser(){
        $sessionManagerMock = $this->createMock(SessionManager::class);
        $sessionManagerMock->expects($this->once())->method('logout');

        $userLoginService = new UserLoginService($sessionManagerMock);

        $user = new User("username");
        $userLoginService->manualLogin($user);

        $answer = $userLoginService->logout($user);
        $this->assertEquals("ok", $answer);
    }

    /**
     * @test
     */
    public function logoutFunctionWithNonExistingUserReturnsError(){
        $sessionManagerDummy = new SessionManagerDummy();

        $userLoginService = new UserLoginService($sessionManagerDummy);

        $user = new User("username");
        $answer = $userLoginService->logout($user);

        $this->assertEquals("User not found", $answer);
    }

    /**
     * @test
     */
    public function givenLoginNotExistingUserReturnsLoginCorrecto(){
        $sessionManagerMock = Mockery::mock(SessionManager::class);
        $sessionManagerMock->expects("login")->with("username", "password")->andReturn(true);

        $userLoginService = new UserLoginService($sessionManagerMock);

        $username = "username";
        $password = "password";
        $answer = $userLoginService->login($username, $password);

        $this->assertEquals("Login correcto", $answer);
    }

    /**
     * @test
     */
    public function givenLoginExistingUserReturnsError(){
        $sessionManagerMock = Mockery::mock(SessionManager::class);
        $sessionManagerMock->expects("login")->with("username", "password")->andReturn(false);

        $userLoginService = new UserLoginService($sessionManagerMock);

        $username = "username";
        $password = "password";
        $userLoginService->login($username, $password);
        $answer = $userLoginService->login($username, $password);

        $this->assertEquals("Login incorrecto", $answer);
    }

    /**
     * @test
     */
    public function givenServiceNotAvailableReturnsExceptionMessage(){
        $sessionManagerMock = Mockery::mock(SessionManager::class);
        $sessionManagerMock->expects("login")->with("username", "password")->andThrowExceptions([new ServiceNotAvailableException("Servicio no disponible")]);

        $userLoginService = new UserLoginService($sessionManagerMock);

        $username = "username";
        $password = "password";
        $userLoginService->login($username, $password);

        $answer = $userLoginService->login($username, $password);
        $this->assertEquals("Servicio no disponible", $answer);
    }

    /**
     * @test
     */
    public function givenUserNotLoggedInReturnsExceptionMessage(){
        $sessionManagerMock = Mockery::mock(SessionManager::class);
        $sessionManagerMock->expects("login")->with("username", "password")->andThrowExceptions([new UserNotLoggedInException("Usuario no loggeado")]);

        $userLoginService = new UserLoginService($sessionManagerMock);

        $username = "username";
        $password = "password";
        $answer = $userLoginService->login($username, $password);

        $this->assertEquals("Usuario no loggeado", $answer);
    }
}
