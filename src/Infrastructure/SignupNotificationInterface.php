<?php
namespace Extasy\Signup\Infrastructure;

use Extasy\Users\User;

interface SignupNotificationInterface
{
    public function inform( User $user );
}