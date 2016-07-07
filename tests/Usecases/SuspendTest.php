<?php
namespace Extasy\Signup\tests\Usecases;

use Extasy\Signup\Usecases\Suspend;
use Extasy\Users\User;

class SuspendTest extends SignupUsecasesBaseTest
{
    const LoginFixture = 'suspendedUser';
    /**
     * @var User
     */
    protected $user;
    public function setUp()
    {
        parent::setUp();
        $this->user = new User([], $this->configurationRepository);
        $this->user->login = self::LoginFixture;

        $this->usersRepository->insert(  $this->user );
    }

    public function testSuspend() {
        $usecase = new Suspend( $this->user, $this->usersRepository);
        $usecase->execute();
        $user = $this->usersRepository->get($this->user->id->getValue());
        $this->assertTrue( !empty( $user->confirmation_code->getValue()));
    }
}