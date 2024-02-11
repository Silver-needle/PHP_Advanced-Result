<?php

namespace GB\HomeTask\Blog\Token;

use DateTimeImmutable;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\AuthException;
use GB\HomeTask\Exceptions\HttpException;
use GB\HomeTask\Http\Request;

class AuthToken
{
    private const HEADER_PREFIX = 'Bearer ';
    public function __construct(
    // Строка токена
        private string $token,
    // UUID пользователя
        private UUID $userUuid,
    // Срок годности
        private DateTimeImmutable $expiresOn
    ) {
    }
    public function token(): string
    {
        return $this->token;
    }
    public function userUuid(): UUID
    {
        return $this->userUuid;
    }
    public function expiresOn(): DateTimeImmutable
    {
        return $this->expiresOn;
    }

    /**
     * @param DateTimeImmutable $expiresOn
     */
    public function setExpiresOn(DateTimeImmutable $expiresOn): void
    {
        $this->expiresOn = $expiresOn;
    }



    public static function removeHeader(Request $request):string
    {
        // Получаем HTTP-заголовок
        try {
            $header = $request->header('Authorization');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        // Проверяем, что заголовок имеет правильный формат
        if (!str_starts_with($header, self::HEADER_PREFIX)) {
            throw new AuthException("Malformed token: [$header]");
        }
        // Отрезаем префикс Bearer
        $token = mb_substr($header, strlen(self::HEADER_PREFIX));
        return $token;
    }

}
