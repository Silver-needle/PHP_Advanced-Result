<?php

namespace GB\HomeTask\Http\Actions\Auth;

use DateTimeImmutable;
use Exception;
use GB\HomeTask\Blog\Token\AuthToken;
use GB\HomeTask\Exceptions\AuthException;
use GB\HomeTask\Http\Actions\ActionInterface;
use GB\HomeTask\Http\Auth\Interfaces\AuthenticationInterface;
use GB\HomeTask\Http\Auth\Interfaces\PasswordAuthenticationInterface;
use GB\HomeTask\Http\ErorrResponse;
use GB\HomeTask\Http\Request;
use GB\HomeTask\Http\Response;
use GB\HomeTask\Http\SuccessResponse;
use GB\HomeTask\Repositories\Tokens\AuthTokensRepositoryInterface;

class LogIn implements ActionInterface
{
    public function __construct(
        // Авторизация по паролю
        private PasswordAuthenticationInterface $passwordAuthentication,
        // Репозиторий токенов
        private AuthTokensRepositoryInterface $authTokensRepository
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(Request $request): Response
    {
        // Аутентифицируем пользователя
        try {
            $user = $this->passwordAuthentication->user($request);
        } catch (AuthException $e) {
            return new ErorrResponse($e->getMessage());
        }
        // Генерируем токен
        $authToken = new AuthToken(
        // Случайная строка длиной 40 символов
            bin2hex(random_bytes(40)),
            $user->getId(),
        // Срок годности - 1 день
            (new DateTimeImmutable())->modify('+1 day')
        );
        // Сохраняем токен в репозиторий
        $this->authTokensRepository->save($authToken);
        // Возвращаем токен
        return new SuccessResponse([
            'token' => (string)$authToken->token(),
        ]);
    }

}
