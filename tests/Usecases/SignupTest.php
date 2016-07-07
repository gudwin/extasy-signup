<?php
namespace Extasy\Signup\tests\Usecases;

use Extasy\Signup\Usecases\Signup;
use Extasy\Users\Search\Request;
use Extasy\Users\Signup\Exceptions\UserAlreadyExistsException;
use Extasy\Users\User;

class SignupTest extends SignupUsecasesBaseTest
{
    const LoginFixture = 'signupUser';
    const EmailFixture = 'sign@up.user';

    /**
     * @var User
     */
    protected $user = null;

    public function setUp()
    {
        parent::setUp();
        $this->user = new User([], $this->configurationRepository);
        $this->user->login = self::LoginFixture;

    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getNotificationInterface()
    {
        $className = '\\Extasy\\Signup\\Infrastructure\\SignupNotificationInterface';
        return $this->getMock( $className );
    }


    /**
     * @expectedException Extasy\Signup\Exceptions\UserAlreadyExistsException
     */
    public function testSignupUserWithSameLogin()
    {
        $notificationInterface = $this->getNotificationInterface();
        $user = new User([], $this->configurationRepository);

        $user->login = self::LoginFixture;
        //
        $usecase = new Signup($this->user, $this->usersRepository, $notificationInterface);
        $usecase->execute();
        //
        $usecase = new Signup($user, $this->usersRepository, $notificationInterface);
        $usecase->execute();
    }

    /**
     * @expectedException Extasy\Signup\Exceptions\UserAlreadyExistsException
     */
    public function testSignupUserWithSameEmail()
    {
        $notificationInterface = $this->getNotificationInterface();
        $user = new User([], $this->configurationRepository);

        $user->email = self::EmailFixture;
        $user->login = 'some_another_login';

        $this->user->email = self::EmailFixture;
        //
        $usecase = new Signup($this->user, $this->usersRepository, $notificationInterface);
        $usecase->execute();
        //
        $usecase = new Signup($user, $this->usersRepository, $notificationInterface);
        $usecase->execute();
    }


    public function testSignupWithEmptyEmail()
    {
        $notificationInterface = $this->getNotificationInterface();

        //
        $usecase = new Signup($this->user, $this->usersRepository, $notificationInterface);
        $usecase->execute();

        $searchRequest = new Request();
        $searchRequest->fields = [
            'login' => self::LoginFixture
        ];
        $user = $this->usersRepository->findOne($searchRequest);

        $this->assertTrue(is_object($user));

    }

    public function testSignup()
    {

        $notificationInterface = $this->getNotificationInterface();
        $notificationInterface->expects($this->once())->method('inform');
        //
        $usecase = new Signup($this->user, $this->usersRepository, $notificationInterface);
        $usecase->execute();

        $searchRequest = new Request();
        $searchRequest->fields = ['login' => self::LoginFixture];

        $user = $this->usersRepository->findOne($searchRequest);
        $this->assertTrue(is_object($user));
        $this->assertTrue(!empty($user->confirmation_code->getValue()));

    }
}