<?php

namespace GB\HomeTask\Http\Auth\Interfaces;

use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Http\Request;

interface AuthenticationInterface
{

    public function user(Request $request): User;

}
