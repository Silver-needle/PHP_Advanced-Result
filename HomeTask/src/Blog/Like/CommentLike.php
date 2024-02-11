<?php

namespace GB\HomeTask\Blog\Like;

use GB\HomeTask\Common\UUID;

class CommentLike extends Like
{
    private UUID $comment;

    /**
     * @param UUID $like
     * @param UUID $comment
     * @param UUID $user
     */
    public function __construct(UUID $like, UUID $comment, UUID $user)
    {
        parent::__construct($like, $user);
        $this->comment = $comment;
    }

    /**
     * @return UUID
     */
    public function getComment(): UUID
    {
        return $this->comment;
    }

    /**
     * @param UUID $comment
     */
    public function setComment(UUID $comment): void
    {
        $this->comment = $comment;
    }

}
