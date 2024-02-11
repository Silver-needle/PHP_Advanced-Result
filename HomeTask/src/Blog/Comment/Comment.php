<?php

namespace GB\HomeTask\Blog\Comment;

use GB\HomeTask\Common\UUID;

class Comment
{
    private ?UUID $id;
    private ?UUID $authorId;
    private ?UUID $articleId;
    private ?string $text;

    /**
     * @param UUID|null $id
     * @param UUID|null $authorId
     * @param UUID|null $articleId
     * @param string|null $text
     */
    public function __construct(?UUID $id, ?UUID $authorId, ?UUID $articleId, ?string $text)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->articleId = $articleId;
        $this->text = $text;
    }

    /**
     * @return UUID|null
     */
    public function getId(): ?UUID
    {
        return $this->id;
    }

    /**
     * @param UUID|null $id
     */
    public function setId(?UUID $id): void
    {
        $this->id = $id;
    }

    /**
     * @return UUID|null
     */
    public function getAuthorId(): ?UUID
    {
        return $this->authorId;
    }

    /**
     * @param UUID|null $authorId
     */
    public function setAuthorId(?UUID $authorId): void
    {
        $this->authorId = $authorId;
    }

    /**
     * @return UUID|null
     */
    public function getArticleId(): ?UUID
    {
        return $this->articleId;
    }

    /**
     * @param UUID|null $articleId
     */
    public function setArticleId(?UUID $articleId): void
    {
        $this->articleId = $articleId;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    public function __toString(): string{
        return ("id=".$this->id.", "."authorId=".$this->authorId.", "."articleId=".$this->articleId.", "."text=".$this->text.PHP_EOL);
    }

}
