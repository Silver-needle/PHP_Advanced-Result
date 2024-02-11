<?php

namespace GB\HomeTask\Repositories\Comments;

use GB\HomeTask\Blog\Comment\Comment;
use GB\HomeTask\Common\UUID;

interface CommentsRepositiryInterface
{
    public function save(Comment $comment): void;
    public function get(UUID $uuid): Comment;
    public function getByAuthor(UUID $id): Comment;
    public function getByArticle(UUID $id): Comment;
}
