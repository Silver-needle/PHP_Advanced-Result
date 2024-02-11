<?php

namespace GB\HomeTask\Http\Actions\Auth;

use DateTimeImmutable;
use GB\HomeTask\Blog\Token\AuthToken;
use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Exceptions\AuthException;
use GB\HomeTask\Exceptions\AuthTokenNotFoundException;
use GB\HomeTask\Exceptions\HttpException;
use GB\HomeTask\Http\Actions\ActionInterface;
use GB\HomeTask\Http\Auth\Interfaces\TokenAuthenticationInterface;
use GB\HomeTask\Http\ErorrResponse;
use GB\HomeTask\Http\Request;
use GB\HomeTask\Http\Response;
use GB\HomeTask\Http\SuccessResponse;
use GB\HomeTask\Repositories\Tokens\AuthTokensRepositoryInterface;

class LogOut implements ActionInterface
{
    private const HEADER_PREFIX = 'Bearer ';

    public function __construct(
        private AuthTokensRepositoryInterface $tokenRepository
    )
    {
    }

    /**
     * @throws AuthException
     */
    public function handle(Request $request): Response
    {
        /*// Получаем HTTP-заголовок
        try {
            $header = $request->header('Authorization');
        } catch (HttpException $e) {
            return new ErorrResponse($e->getMessage());
        }

        // Проверяем, что заголовок имеет правильный формат
        if (!str_starts_with($header, self::HEADER_PREFIX)) {
            return new ErorrResponse("Malformed token: [$header]");
        }
        // Отрезаем префикс Bearer
        $token = mb_substr($header, strlen(self::HEADER_PREFIX));*/
        try {
            $token = AuthToken::removeHeader($request);
        }catch (AuthException $e){
            return new ErorrResponse($e->getMessage());
        }
        // Ищем токен в репозитории
        try {
            $authToken = $this->tokenRepository->get($token);
        } catch (AuthTokenNotFoundException) {
            return new ErorrResponse("Bad token: [$token]");
        }

        $authToken->setExpiresOn(new DateTimeImmutable());

        $this->tokenRepository->save($authToken);

        return new SuccessResponse([
            'message'=> 'Success log out!'
        ]);
    }

}
