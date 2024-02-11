<?php

namespace GB\HomeTask\Repositories\Users;

use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Common\UUID;

interface UsersRepositoryInterface
{

    public function save(User $user): void;

    public function get(UUID $uuid): User;

    public function getByUsername(string $username): User;

}
