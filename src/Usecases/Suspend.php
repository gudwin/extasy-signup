<?php
namespace Extasy\Signup\Usecases;

use Extasy\Usecase\Usecase;
use Extasy\Users\RepositoryInterface;
use Extasy\Users\User;

class Suspend {
    use Usecase;
    /**
     * @var null
     */
    protected $repositoryInterface = null;

    /**
     * @var User
     */
    protected $user;
    public function __construct( User $user, RepositoryInterface $repositoryInterface )
    {
        $this->repositoryInterface = $repositoryInterface;
        $this->user = $user;
    }

    protected function action() {
        $this->user->confirmation_code->generate();
        $this->repositoryInterface->update( $this->user );
    }
}