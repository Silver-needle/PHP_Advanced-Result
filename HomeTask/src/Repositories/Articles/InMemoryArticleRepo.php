<?php

namespace GB\HomeTask\Repositories\Articles;

use GB\HomeTask\Blog\Article\Article;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\UserNotFoundException;

class InMemoryArticleRepo implements ArticlesRepositoryInterface
{
    private array $articles = [];
    public function save(Article $article): void
    {
        $this->articles[] = $article;
    }

    /**
     * @throws UserNotFoundException
     */
    public function get(UUID $uuid): Article
    {
        foreach ($this->articles as $article) {
            if ((string)$article->getId() === (string)$uuid) {
                return $article;
            }
        }
        throw new UserNotFoundException("Article not found: $uuid");
    }

    /**
     * @throws UserNotFoundException
     */
    public function getByTitle(string $title): Article
    {
        foreach ($this->articles as $article) {
            if ($article->getArticle() === $title) {
                return $article;
            }
        }
        throw new UserNotFoundException("User not found: $title");
    }

    /**
     * @throws UserNotFoundException
     */
    public function getByAuthor(UUID $id): Article
    {
        foreach ($this->articles as $article) {
            if ((string)$article->getAuthorId() === (string)$id) {
                return $article;
            }
        }
        throw new UserNotFoundException("User not found: $id");
    }
}
