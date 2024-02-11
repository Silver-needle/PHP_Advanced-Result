<?php

namespace GB\HomeTask\Repositories\Likes;

use GB\HomeTask\Blog\Article\Article;
use GB\HomeTask\Blog\Like\ArticleLike;
use GB\HomeTask\Blog\Like\Like;
use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\InvalidArgumentException;
use GB\HomeTask\Exceptions\LikeExsistException;
use GB\HomeTask\Exceptions\LikeNotFoundException;
use PDO;
use PDOStatement;
use PHPUnit\Util\Exception;
use Psr\Log\LoggerInterface;

class SqLiteArticleLikesRepo
{
    private PDO $connection;
    private LoggerInterface $logger;

    /**
     * @param PDO $connection
     * @param LoggerInterface $logger
     */
    public function __construct(PDO $connection, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    public function save(Like $like): void
    {
        $this->logger->info("Started saving the article like to database");
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

    /**
     * @throws LikeNotFoundException
     * @throws InvalidArgumentException
     */
    public function get(UUID $uuid): ArticleLike
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

    /**
     * @throws LikeNotFoundException
     * @throws InvalidArgumentException
     */
    private function getLike(PDOStatement $statement, string $id): ArticleLike
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            $this->logger->warning("Cannot find the article like by $id");
            throw new LikeNotFoundException(
                "Cannot find like: $id"
            );
        }
        // Создаём объект пользователя с полем username
        return new ArticleLike(new UUID($result['uuid']), new UUID($result['articleUuid']), new UUID($result['userUuid']));
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

    /**
     * @throws LikeExsistException
     */
    public function likeExist(Article $article, User $user): void
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM article_likes WHERE articleUuid = :articleUuid AND userUuid = :userUuid'
        );
        $statement->execute([
            ':articleUuid' => (string)$article->getId(),
            ':userUuid' => (string)$user->getId()
        ]);

        $isExisted = $statement->fetch(PDO::FETCH_ASSOC);

        if($isExisted){
            throw new LikeExsistException("The users like for this post already exist");
        }
    }

}
