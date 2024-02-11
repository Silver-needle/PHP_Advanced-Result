<?php

namespace GB\HomeTask\Http\Auth;

use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Http\Auth\Interfaces\IdentificationInterface;
use GB\HomeTask\Http\Request;
use GB\HomeTask\Repositories\Users\UsersRepositoryInterface;

class JsonBodyUsernameIdentification implements IdentificationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {}

    public function user(Request $request): User
    {

    }

}
