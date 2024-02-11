<?php

use GB\HomeTask\Blog\Article\Article;
use GB\HomeTask\Blog\Article\CreateArticleCommand;
use GB\HomeTask\Common\Arguments;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\ArgumentsException;
use GB\HomeTask\Exceptions\CommandException;
use GB\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use GB\HomeTask\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;

class ArticleCommandClassTest extends TestCase
{
    private function getRepo()
    {
        return new class implements ArticlesRepositoryInterface {
            private bool $callback = false;
            public function save(Article $article): void
            {
                $this->callback = true;
            }


            public function getCallback(): bool
            {
                return $this->callback;
            }

            public function getByTitle(string $title): Article
            {
                // TODO: Implement getByTitle() method.
            }

            public function getByAuthor(UUID $id): Article
            {
                // TODO: Implement getByAuthor() method.
            }

            public function get(UUID $uuid): Article
            {
                // TODO: Implement get() method.
            }

            public function deleteById(UUID $id)
            {
                // TODO: Implement deleteById() method.
            }
        };
    }

    /**
     * @throws ArgumentsException
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     * @throws CommandException
     * @throws ArgumentsException
     */
    public function testItSavesUserToRepository():void{

        $obj = $this->getRepo();

        $userCom = new CreateArticleCommand($obj, new DummyLogger());

        $userCom->handle(
            new Arguments([
            'title' => 'title',
            'text' => 'text'
        ]),
            new UUID('123e4567-e89b-12d3-a456-426614174001'));

        $this->assertTrue($obj->getCallback());
    }
}
