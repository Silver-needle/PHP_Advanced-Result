<?php

namespace GB\HomeTask\Repositories\Likes;

use GB\HomeTask\Blog\Article\Article;
use GB\HomeTask\Blog\Comment\Comment;
use GB\HomeTask\Blog\Like\CommentLike;
use GB\HomeTask\Blog\Like\Like;
use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\InvalidArgumentException;
use GB\HomeTask\Exceptions\LikeExsistException;
use GB\HomeTask\Exceptions\LikeNotFoundException;
use PDO;
use PDOStatement;
use Psr\Log\LoggerInterface;

class SqLiteCommentLikesRepo
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
        $this->logger->info("Started saving the comment like to database");
        // Добавили поле username в запрос
        $statement = $this->connection->prepare(
            'INSERT INTO comment_likes (uuid, commentUuid, userUuid)
            VALUES (:uuid, :commentUuid, :userUuid)'
        );
        $statement->execute([
            ':uuid'=> (string)$like->getLike(),
            ':commentUuid'=> $like->getComment(),
            ':userUuid'=> $like->getUser()
        ]);
    }

    /**
     * @throws LikeNotFoundException
     * @throws InvalidArgumentException
     */
    public function get(UUID $uuid): CommentLike
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comment_likes WHERE uuid = :uuid'
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
    public function getAllByComment(UUID $id): iterable
    {
        $likes = [];

        $statement = $this->connection->prepare(
            'SELECT * FROM comment_likes WHERE commentUuid = :uuid'
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
    private function getLike(PDOStatement $statement, string $id): CommentLike
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            $this->logger->warning("Cannot find the comment like by $id");
            throw new LikeNotFoundException(
                "Cannot find like: $id"
            );
        }
        // Создаём объект пользователя с полем username
        return new CommentLike(new UUID($result['uuid']), new UUID($result['articleUuid']), new UUID($result['userUuid']));
    }

    private function getAllLikes(PDOStatement $statement): iterable
    {
        $likes = [];
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new LikeNotFoundException(
                "Cannot find likes on this comment."
            );
        }
        return $likes;
    }

    public function likeExist(Comment $comment, User $user): void
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comment_likes WHERE commentUuid = :commentUuid AND userUuid = :userUuid'
        );
        $statement->execute([
            ':commentUuid' => (string)$comment->getId(),
            ':userUuid' => (string)$user->getId()
        ]);

        $isExisted = $statement->fetch(PDO::FETCH_ASSOC);

        if($isExisted){
            throw new LikeExsistException("The users like for this comment already exist");
        }
    }
}
