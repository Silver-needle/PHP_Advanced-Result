<?php

use GB\HomeTask\Blog\User\CreateUserCommand;
use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Common\Arguments;
use GB\HomeTask\Common\Name;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\ArgumentsException;
use GB\HomeTask\Exceptions\CommandException;
use GB\HomeTask\Exceptions\UserNotFoundException;
use GB\HomeTask\Repositories\Users\UsersRepositoryInterface;
use GB\HomeTask\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;

class UserCommandClassTest extends TestCase
{
    private function getRepo()
    {
        return new class implements UsersRepositoryInterface {
            private bool $callback = false;
            public function save(User $user): void
            {
                $this->callback = true;
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getCallback(): bool
            {
                return $this->callback;
            }
        };
    }

    /**
     * @throws ArgumentsException
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     * @throws CommandException
     */
    public function testItSavesUserToRepository():void{

        $obj = $this->getRepo();

        $userCom = new CreateUserCommand($obj, new DummyLogger());

        $userCom->handle(new Arguments([
            'username' => 'Ivan',
            'first_name' => 'Ivan',
            'last_name' => 'Ivanov',
            'password' => 'test'
        ]));

        $this->assertTrue($obj->getCallback());
    }

    /**
     * @throws ArgumentsException
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     * @throws CommandException
     */
    public function testExceptionWhenUserExist():void{
        $userRepo = new class implements UsersRepositoryInterface {
            public function save(User $user): void
            {
            }

            public function get(UUID $uuid): User
            {
                return new User(new UUID('123e4567-e89b-12d3-a456-426614174000'), 'ivan228', "test", new Name('Ivan', 'Ivanov'));
            }

            public function getByUsername(string $username): User
            {
                return new User(new UUID('123e4567-e89b-12d3-a456-426614174000'), 'ivan228', "test", new Name('Ivan', 'Ivanov'));
            }
        };

        $userCommand = new CreateUserCommand($userRepo, new DummyLogger());

        $this->expectException(CommandException::class);
        $this->expectExceptionMessage("User already exists: ivan228");

        $userCommand->handle(new Arguments([
            'username' => 'ivan228',
            'first_name' => 'Ivan',
            'last_name' => 'Ivanov',
            'password' => 'test'
        ]));
    }
}
