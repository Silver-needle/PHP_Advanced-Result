<?php

namespace GB\HomeTask\Blog\Like;

use GB\HomeTask\Common\UUID;

class Like
{
    private UUID $like;
    private UUID $user;

    /**
     * @param UUID $like
     * @param UUID $user
     */
    public function __construct(UUID $like, UUID $user)
    {
        $this->like = $like;
        $this->user = $user;
    }

    /**
     * @return UUID
     */
    public function getLike(): UUID
    {
        return $this->like;
    }

    /**
     * @param UUID $like
     */
    public function setLike(UUID $like): void
    {
        $this->like = $like;
    }

    /**
     * @return UUID
     */
    public function getUser(): UUID
    {
        return $this->user;
    }

    /**
     * @param UUID $user
     */
    public function setUser(UUID $user): void
    {
        $this->user = $user;
    }

    public function __toString(): string{
        return ("id=".$this->like.","."userId=".$this->user.PHP_EOL);
    }

}
