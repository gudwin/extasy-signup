<?php
namespace Extasy\Signup\tests\Usecases;

use Extasy\Users\tests\BaseTest;
use Extasy\Users\tests\Samples\MemoryUsersRepository;

abstract  class SignupUsecasesBaseTest extends BaseTest
{
    /**
     * @var MemoryUsersRepository
     */
    protected $usersRepository = null;
    public function setUp()
    {
        parent::setUp();
        $this->usersRepository = new MemoryUsersRepository();
    }
}