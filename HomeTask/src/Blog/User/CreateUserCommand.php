<?php

namespace GB\HomeTask\Blog\User;

use GB\HomeTask\Common\Arguments;
use GB\HomeTask\Common\Name;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\ArgumentsException;
use GB\HomeTask\Exceptions\CommandException;
use GB\HomeTask\Exceptions\InvalidArgumentException;
use GB\HomeTask\Exceptions\UserNotFoundException;
use GB\HomeTask\Repositories\Users\UsersRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateUserCommand
{
    private UsersRepositoryInterface $usersRepository;

    public function __construct(UsersRepositoryInterface $usersRepository, LoggerInterface $logger) {
        $this->usersRepository = $usersRepository;
        $this->logger = $logger;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ArgumentsException
     * @throws CommandException
     */
    public function handle(Arguments $arguments):void{
        $this->logger->info("Started created new user by command line");
        $id = UUID::random();
        $name = new Name($arguments->getArg('first_name'), $arguments->getArg('last_name'));
        $username = $arguments->getArg('username');
        $password = $arguments->getArg('password');

        if($this->UserExist($username)){
            throw new CommandException("User already exists: $username");
        }

        $user = User::createFrom($username, $password, $name);

        $this->usersRepository->save($user);
    }

    public function UserExist($username):bool{
        try {
            $user = $this->usersRepository->getByUsername($username);
        }catch (UserNotFoundException $e){
            return false;
        }
        return true;
    }

}
