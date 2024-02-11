<?php

namespace GB\HomeTask\Repositories\Articles;

use GB\HomeTask\Blog\Article\Article;
use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Common\UUID;

interface ArticlesRepositoryInterface
{
    public function save(Article $article): void;
    public function get(UUID $uuid): Article;
    public function getByTitle(string $title): Article;
    public function getByAuthor(UUID $id):Article;
    public function deleteById(UUID $id);
}
