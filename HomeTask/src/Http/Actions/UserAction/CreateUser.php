<?php

namespace GB\HomeTask\Http\Actions\UserAction;

use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Common\Name;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\HttpException;
use GB\HomeTask\Exceptions\InvalidArgumentException;
use GB\HomeTask\Http\Actions\ActionInterface;
use GB\HomeTask\Http\ErorrResponse;
use GB\HomeTask\Http\Request;
use GB\HomeTask\Http\Response;
use GB\HomeTask\Http\SuccessResponse;
use GB\HomeTask\Repositories\Users\UsersRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateUser implements ActionInterface
{

    // Нам понадобится репозиторий пользователей,
    // внедряем его контракт в качестве зависимости
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function handle(Request $request): Response
    {
        $this->logger->info("Started created new user");
        $id = UUID::random();
        try{
            $first_name = $request->jsonBodyField('first_name');
            $username = $request->jsonBodyField('username');
            $last_name = $request->jsonBodyField('last_name');
            $password = $request->jsonBodyField('password');
        }catch (HttpException $exception){
            $this->logger->warning($exception->getMessage(), ["error"=> $exception]);
            return new ErorrResponse($exception->getMessage());
        }

        $user = User::createFrom($username, $password, new Name($first_name, $last_name));

        $this->usersRepository->save($user);
        return new SuccessResponse([
            "message"=> "User successful created with Id= $id",
        ]);
    }

}
