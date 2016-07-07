<?php
namespace Extasy\Signup\Usecases;

use Extasy\Usecase\Usecase;
use Extasy\Users\RepositoryInterface;
use Extasy\Users\Search\Request;
use Extasy\Users\User;

class Delete {
    use Usecase;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var RepositoryInterface
     */
    protected $repository = null;

    public function __construct( User $user, RepositoryInterface $repository  )
    {
        $this->user = $user;
        $this->repository = $repository;
    }

    protected function action() {
        $this->repository->delete( $this->user );
    }
}