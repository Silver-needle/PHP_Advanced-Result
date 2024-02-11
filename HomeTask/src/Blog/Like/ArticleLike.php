<?php

namespace GB\HomeTask\Blog\Like;

use GB\HomeTask\Common\UUID;

class ArticleLike extends Like
{
    private UUID $article;

    /**
     * @param UUID $like
     * @param UUID $article
     * @param UUID $user
     */
    public function __construct(UUID $like, UUID $article, UUID $user)
    {
        parent::__construct($like, $user);
        $this->article = $article;
    }

    /**
     * @return UUID
     */
    public function getArticle(): UUID
    {
        return $this->article;
    }

    /**
     * @param UUID $article
     */
    public function setArticle(UUID $article): void
    {
        $this->article = $article;
    }

}
