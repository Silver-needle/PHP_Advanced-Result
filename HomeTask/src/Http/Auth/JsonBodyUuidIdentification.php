<?php

namespace GB\HomeTask\Http\Auth;

use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\AuthException;
use GB\HomeTask\Exceptions\HttpException;
use GB\HomeTask\Exceptions\InvalidArgumentException;
use GB\HomeTask\Exceptions\UserNotFoundException;
use GB\HomeTask\Http\Auth\Interfaces\IdentificationInterface;
use GB\HomeTask\Http\Request;
use GB\HomeTask\Repositories\Users\UsersRepositoryInterface;

class JsonBodyUuidIdentification implements IdentificationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {}

    /**
     * @throws AuthException
     */
    public function user(Request $request): User
    {
        try {
        // Получаем UUID пользователя из JSON-тела запроса;
        // ожидаем, что корректный UUID находится в поле user_uuid
            $userUuid = new UUID($request->jsonBodyField('authorId'));
        } catch (HttpException|InvalidArgumentException $e) {
        // Если невозможно получить UUID из запроса -
        // бросаем исключение
            throw new AuthException($e->getMessage());
        }
        try {
        // Ищем пользователя в репозитории и возвращаем его
            return $this->usersRepository->get($userUuid);
        } catch (UserNotFoundException $e) {
        // Если пользователь с таким UUID не найден -
        // бросаем исключение
            throw new AuthException($e->getMessage());
        }
    }

}
