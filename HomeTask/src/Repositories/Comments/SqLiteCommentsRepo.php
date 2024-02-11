<?php

namespace GB\HomeTask\Repositories\Comments;

use GB\HomeTask\Blog\Comment\Comment;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\CommentNotFoundException;
use GB\HomeTask\Exceptions\UserNotFoundException;
use PDO;
use PDOStatement;
use Psr\Log\LoggerInterface;

class SqLiteCommentsRepo implements CommentsRepositiryInterface {

    //Поле, где хранится коннект к базе данных с данными.
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

    public function save(Comment $comment): void
    {
        $this->logger->info("Started saving the comment to database");
        // Добавили поле username в запрос
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, authorId, articleId, text)
            VALUES (:uuid, :authorId, :articleId, :text)'
        );
        $statement->execute([
            ':uuid' => (string)$comment->getId(),
            ':authorId' => $comment->getAuthorId(),
            ':articleId' => $comment->getArticleId(),
            ':text' => $comment->getText()]);
    }

    /**
     * @throws UserNotFoundException
     */
    public function get(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        return $this->getComment($statement, $uuid);
    }

    /**
     * @throws UserNotFoundException
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     */
    public function getByAuthor(UUID $id): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE authorId = :authorId'
        );
        $statement->execute([
            ':authorId' => (string)$id,
        ]);
        return $this->getComment($statement, "by authorId".$id);
    }

    /**
     * @throws UserNotFoundException
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     */
    public function getByArticle(UUID $id): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE articleId = :articleId'
        );
        $statement->execute([
            ':articleId' => (string)$id,
        ]);
        return $this->getComment($statement, "by articleId".$id);
    }

    /**
     * @throws UserNotFoundException
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     */
    private function getComment(PDOStatement $statement, string $id): Comment
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            $this->logger->warning("Cannot find the comment by $id ");
            throw new CommentNotFoundException(
                "Cannot find comment: $id"
            );
        }
        // Создаём объект пользователя с полем username
        return new Comment(new UUID($result['uuid']), new UUID($result['authorId']), new UUID($result['articleId']), $result['text']);
    }
}
