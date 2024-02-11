<?php

namespace GB\HomeTask\Blog\Article;

use GB\HomeTask\Common\UUID;

class Article
{
    private ?UUID $id;
    private ?UUID $authorId;
    private ?string $title;
    private ?string $text;

    /**
     * @param UUID|null $id
     * @param UUID|null $authorId
     * @param string|null $title
     * @param string|null $text
     */
    public function __construct(?UUID $id, ?UUID $authorId, ?string $title, ?string $text)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->title = $title;
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
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
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
        return ("id=".$this->id.", "."authorId=".$this->authorId.", "."title=".$this->title.", "."text=".$this->text.PHP_EOL);
    }

}
