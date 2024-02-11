<?php

namespace GB\HomeTask\Repositories\Likes;

use GB\HomeTask\Blog\Article\Article;
use GB\HomeTask\Blog\Like\Like;
use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\LikeNotFoundException;
use PDO;
use PDOStatement;

class SqLiteLikesRepo implements LikesRepositoryInterface
{
    private PDO $connection;

    /**
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Like $like): void
    {
        // Добавили поле username в запрос
        $statement = $this->connection->prepare(
            'INSERT INTO article_likes (uuid, articleUuid, userUuid)
            VALUES (:uuid, :articleUuid, :userUuid)'
        );
        $statement->execute([
            ':uuid'=> (string)$like->getLike(),
            ':articleUuid'=> $like->getArticle(),
            ':userUuid'=> $like->getUser()
        ]);
    }

    public function get(UUID $uuid): Like
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM article_likes WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        return $this->getLike($statement, $uuid);
    }

    public function getByAuthor(UUID $id): Like
    {
        // TODO: Implement getByAuthor() method.
    }

    public function getByArticle(UUID $id): Like
    {
        // TODO: Implement getByArticle() method.
    }

    public function getAllByAuthor(UUID $id): iterable
    {
        // TODO: Implement getAllByAuthor() method.
    }

    /**
     * @throws LikeNotFoundException
     */
    public function getAllByArticle(UUID $id): iterable
    {
        $likes = [];

        $statement = $this->connection->prepare(
            'SELECT * FROM article_likes WHERE articleUuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$id,
        ]);
        return $this->getAllLikes($statement);
    }

    private function getLike(PDOStatement $statement, string $id): Like
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new LikeNotFoundException(
                "Cannot find like: $id"
            );
        }
        // Создаём объект пользователя с полем username
        return new Like(new UUID($result['uuid']), new UUID($result['articleUuid']), new UUID($result['userUuid']));
    }

    private function getAllLikes(PDOStatement $statement): iterable
    {
        $likes = [];
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new LikeNotFoundException(
                "Cannot find likes on this article."
            );
        }
        return $likes;
    }

    public function likeExist(Article $article, User $user): void
    {

    }

}
