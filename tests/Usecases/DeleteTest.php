<?php
namespace Extasy\Signup\tests\Usecases;

use Extasy\Model\NotFoundException;
use Extasy\Signup\Usecases\Delete;
use  Extasy\Users\User;

class DeleteTest extends SignupUsecasesBaseTest
{

    const LoginFixture = 'user4deletion';

    /**
     * @var User
     */
    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = new User([], $this->configurationRepository);
        $this->user->login = self::LoginFixture;
        $this->usersRepository->insert($this->user);
    }

    public function testDeleteUser()
    {
        $usecase = new Delete($this->user, $this->usersRepository);
        $usecase->execute();

        try {
            $this->usersRepository->get($this->user->id->getValue());
            $this->fail('User not deleted');
        } catch ( NotFoundException $e ) {

        }

    }
}