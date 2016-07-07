<?php
namespace Extasy\Signup\Usecases;

use Extasy\Signup\Exceptions\EmptyLoginException;
use Extasy\Signup\Exceptions\UserAlreadyExistsException;
use Extasy\Signup\Infrastructure\SignupNotificationInterface;
use Extasy\Usecase\Usecase;
use Extasy\Users\Search\Request;
use Extasy\Users\User;
use Extasy\Users\RepositoryInterface;

class Signup {
    use Usecase;
    /**
     * @var User
     */
    protected $user;
    /**
     * @var RepositoryInterface
     */
    protected $repositoryInterface;
    /**
     * @var SignupNotificationInterface
     */
    protected $notificationInterface;

    public function __construct( User $user, RepositoryInterface $repositoryInterface, SignupNotificationInterface $notificationInterface) {
        $this->user = $user;
        $this->repositoryInterface = $repositoryInterface;
        $this->notificationInterface = $notificationInterface;
    }
    protected function action() {
        $this->validateUser();
        $this->user->confirmation_code->generate();
        $this->repositoryInterface->insert( $this->user );
        $this->notificationInterface->inform( $this->user );
    }
    protected function validateUser() {
        $this->validateLogin();
        $this->validateEmail();
    }
    protected function validateLogin() {
        $isLoginEmpty = empty($this->user->login->getValue());
        if ( $isLoginEmpty  ) {
            throw new EmptyLoginException('Login empty');
        }
        $searchRequest = new Request();
        $searchRequest->fields = [
            'login' => $this->user->login->getValue()
        ];
        $found = $this->repositoryInterface->findOne( $searchRequest );
        if  (!empty( $found )) {
            throw new UserAlreadyExistsException('Login already registered');
        }
    }
    protected function  validateEmail() {
        if ( empty( $this->user->email->getValue() )) {
            return ;
        }
        $searchRequest = new Request();
        $searchRequest->fields = [
            'email' => $this->user->email->getValue()
        ];
        $found = $this->repositoryInterface->findOne( $searchRequest );
        if  (!empty( $found )) {
            throw new UserAlreadyExistsException('Email already registered');
        }
    }
}