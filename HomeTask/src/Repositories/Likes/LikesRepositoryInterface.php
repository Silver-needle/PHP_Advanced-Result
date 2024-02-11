<?php

namespace GB\HomeTask\Repositories\Likes;

use GB\HomeTask\Blog\Article\Article;
use GB\HomeTask\Blog\Like\Like;
use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Common\UUID;

interface LikesRepositoryInterface
{
    public function save(Like $comment): void;
    public function get(UUID $uuid): Like;
    public function getByAuthor(UUID $id): Like;
    public function getByArticle(UUID $id): Like;
    public function getAllByAuthor(UUID $id): iterable;
    public function getAllByArticle(UUID $id):iterable;
    public function likeExist(Article $article, User $user):void;
}
