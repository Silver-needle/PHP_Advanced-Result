<?php

namespace GB\HomeTask\Repositories\Users;

use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\UserNotFoundException;

class InMemoryUsersRepo implements UsersRepositoryInterface
{
    private array $users = [];

    public function save(User $user):void{
        $this->users[]=$user;
    }

    /**
     * @throws UserNotFoundException
     */
    public function get(UUID $id):User{
        foreach ($this->users as $user) {
            if ((string)$user->getId() === (string)$id) {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found: $id");

    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @param array $users
     */
    public function setUsers(array $users): void
    {
        $this->users = $users;
    }

    /**
     * @throws UserNotFoundException
     */
    public function getByUsername(string $username): User
    {
        foreach ($this->users as $user) {
            if ($user->username() === $username) {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found: $username");
    }

}
