<?php


namespace Extasy\Signup\Usecases;

use Extasy\Model\NotFoundException;
use Extasy\Signup\Infrastructure\UserConfirmedNotificationInterface;
use Extasy\Usecase\Usecase;
use Extasy\Users\RepositoryInterface;
use Extasy\Users\Search\Request;
use \InvalidArgumentException;
class Confirm
{
    use Usecase;

    /**
     * @var RepositoryInterface
     */
    protected $repository = null;

    protected $confirmationCode = '';

    /**
     * @var UserConfirmedNotificationInterface
     */
    protected $notificationInterface = null;

    public function __construct(
        $confirmationCode,
        RepositoryInterface $repositoryInterface,
        UserConfirmedNotificationInterface $notificationInterface
    ) {
        $this->repository = $repositoryInterface;
        $this->confirmationCode = $confirmationCode;
        $this->notificationInterface = $notificationInterface;
    }

    protected function action()
    {
        $user = $this->getUser();
        $user->confirmation_code = '';
        $this->repository->update($user);
        $this->notificationInterface->inform($user);
    }

    /**
     * @return \Extasy\Users\User
     */
    protected function getUser()
    {
        if ( empty( $this->confirmationCode )) {
            throw new InvalidArgumentException('Confirmation code couldn`t be empty');
        }
        $request = new Request();
        $request->fields = [
            'confirmation_code' => $this->confirmationCode
        ];
        $found = $this->repository->findOne($request);
        if (empty($found)) {
            throw new NotFoundException('User not found');
        }
        return $found;
    }
}