<?php
namespace Extasy\Signup\tests\Usecases;

use Extasy\Model\NotFoundException;
use Extasy\Signup\Infrastructure\UserConfirmedNotificationInterface;
use Extasy\Signup\Usecases\Confirm;
use Extasy\Users\User;

class ConfirmTest extends SignupUsecasesBaseTest
{
    const UserLogin = 'hello';
    const Fixture = 'Hello world!';

    /**
     * @var User
     */
    protected $user = null;

    public function setUp()
    {
        parent::setUp();
        $this->user = new User([], $this->configurationRepository);
        $this->user->login = self::UserLogin;

        $this->usersRepository->insert($this->user);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getNotificationInterface()
    {
        $className = '\\Extasy\\Signup\\Infrastructure\\UserConfirmedNotificationInterface';
        $result = $this->getMock($className);
        return $result;

    }

    public function testConfirmUser()
    {
        $notificationInterface = $this->getNotificationInterface();
        $notificationInterface->expects( $this->once())->method('inform');
        //
        $this->user->confirmation_code = self::Fixture;

        $usecase = new Confirm(self::Fixture, $this->usersRepository, $notificationInterface);
        $usecase->execute();


        $user = $this->usersRepository->get($this->user->id->getValue());
        $this->assertTrue(empty($user->confirmation_code->getValue()));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConfirmWithEmptyKey()
    {
        $notificationInterface = $this->getNotificationInterface();
        $usecase = new Confirm('', $this->usersRepository, $notificationInterface);
        $usecase->execute();
    }

    /**
     * @expectedException \Extasy\Model\NotFoundException
     */
    public function testConfirmWithWrongKey()
    {
        $notificationInterface = $this->getNotificationInterface();
        $usecase = new Confirm('123', $this->usersRepository, $notificationInterface);
        $usecase->execute();
    }

}