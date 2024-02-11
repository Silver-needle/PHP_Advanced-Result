<?php

use GB\HomeTask\Blog\Comment\Comment;
use GB\HomeTask\Blog\Comment\CreateCommentCommand;
use GB\HomeTask\Common\Arguments;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\ArgumentsException;
use GB\HomeTask\Exceptions\CommandException;
use GB\HomeTask\Repositories\Comments\CommentsRepositiryInterface;
use GB\HomeTask\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;

class CommentCommandClassTest extends TestCase
{
    private function getRepo()
    {
        return new class implements  CommentsRepositiryInterface {
            private bool $callback = false;
            public function save(Comment $comment): void
            {
                $this->callback = true;
            }

            public function getCallback(): bool
            {
                return $this->callback;
            }

            public function get(UUID $uuid): Comment
            {
                // TODO: Implement get() method.
            }

            public function getByAuthor(UUID $id): Comment
            {
                // TODO: Implement getByAuthor() method.
            }

            public function getByArticle(UUID $id): Comment
            {
                // TODO: Implement getByArticle() method.
            }
        };
    }

    /**
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     * @throws CommandException
     * @throws ArgumentsException
     */
    public function testItSavesUserToRepository():void{

        $obj = $this->getRepo();

        $userCom = new CreateCommentCommand($obj, new DummyLogger());

        $userCom->handle(
            new Arguments([
                'title' => 'title',
                'text' => 'text'
            ]),
            new UUID('123e4567-e89b-12d3-a456-426614174001'), new UUID('123e4567-e89b-12d3-a456-426614174002'));

        $this->assertTrue($obj->getCallback());
    }
}
