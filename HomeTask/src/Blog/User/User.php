<?php

namespace GB\HomeTask\Blog\User;

use GB\HomeTask\Common\Name;
use GB\HomeTask\Common\UUID;

class User
{
    private ?UUID$id;
    private ?Name $name;
    private ?string $username;
    private string $hashedPassword;

    /**
     * @return string
     */
    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }

    /**
     * @param string $hashedPassword
     */
    public function setHashedPassword(string $hashedPassword): void
    {
        $this->hashedPassword = $hashedPassword;
    }

    private static function hash(string $password, UUID $uuid): string
    {
        // Используем UUID в качестве соли
        return hash('sha256', $uuid . $password);
    }


    // Функция для проверки предъявленного пароля
    public function checkPassword(string $password): bool
    {
        // Передаём UUID пользователя
        // в функцию хеширования пароля
        return $this->hashedPassword
            === self::hash($password, $this->id);
    }


    public static function createFrom(
        string $username,
        string $password,
        Name $name
    ): self {
// Генерируем UUID
        $uuid = UUID::random();
        return new self(
            $uuid,
            $username,
            // Передаём сгенерированный UUID
            // в функцию хеширования пароля
            self::hash($password, $uuid),
            $name
        );
    }

    /**
     * @param UUID|null $id
     * @param Name|null $name
     * @param string|null $username
     */
    public function __construct(?UUID $id,?string $username, string $hashedPassword, ?Name $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->hashedPassword = $hashedPassword;
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return UUID|null
     */
    public function getId(): ?UUID
    {
        return $this->id;
    }

    /**
     * @param UUID|null $id
     */
    public function setId(?UUID $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Name|null
     */
    public function getName(): ?Name
    {
        return $this->name;
    }

    /**
     * @param Name|null $name
     */
    public function setName(?Name $name): void
    {
        $this->name = $name;
    }

    public function __toString(): string{
        return ("id=".$this->id.", "."name=".$this->name->getFirstName().", "."lastname=".$this->name->getLastName().", username=".$this->username.PHP_EOL);
    }

}
