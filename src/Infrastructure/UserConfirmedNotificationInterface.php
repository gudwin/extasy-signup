<?php


namespace Extasy\Signup\Infrastructure;


use Extasy\Users\User;

interface UserConfirmedNotificationInterface
{
    public function inform( User $user );
}