<?php

namespace GB\HomeTask\Repositories\Tokens;

use GB\HomeTask\Blog\Token\AuthToken;

interface AuthTokensRepositoryInterface
{
    // Метод сохранения токена
    public function save(AuthToken $authToken): void;

    // Метод получения токена
    public function get(string $token): AuthToken;

    public function expiredToken(string $token):void;

}
