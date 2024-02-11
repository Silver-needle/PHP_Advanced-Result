<?php

use GB\HomeTask\Blog\Comment\Comment;
use GB\HomeTask\Common\UUID;
use PHPUnit\Framework\TestCase;

class CommentClassTest extends TestCase
{
    public function testConstructor():void{
        $arg = new Comment(new UUID("123e4567-e89b-12d3-a456-426614174000"), new UUID("123e4567-e89b-12d3-a456-426614174001"), new UUID("123e4567-e89b-12d3-a456-426614174002"), "test_text");
        $value = (string)$arg;
        $this->assertEquals("id=123e4567-e89b-12d3-a456-426614174000, authorId=123e4567-e89b-12d3-a456-426614174001, articleId=123e4567-e89b-12d3-a456-426614174002, text=test_text",trim($value));
    }

    /**
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     */
    private function getComment(): Comment
    {
        return new Comment(UUID::random(),UUID::random(),UUID::random(), "text" );
    }

    private function CommentsProviderForMethodSetAndGetId():iterable{
        return [ ["123e4567-e89b-12d3-a456-426614174111", "123e4567-e89b-12d3-a456-426614174111"],
            ["123e4567-e89b-12d3-a456-426614174222", "123e4567-e89b-12d3-a456-426614174222"],
            ["123e4567-e89b-12d3-a456-426614174333", "123e4567-e89b-12d3-a456-426614174333"]
        ];
    }

    /**
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     * @dataProvider CommentsProviderForMethodSetAndGetId
     */
    public function testCommentSetAndGetId($inputValue, $expectedValue){
        $article = $this->getComment();
        $article->setId(new UUID($inputValue));

        $this->assertEquals($expectedValue, (string)$article->getId());
    }

    /**
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     * @dataProvider CommentsProviderForMethodSetAndGetId
     */
    public function testCommentSetAndGetAuthorId($inputValue, $expectedValue){
        $article = $this->getComment();
        $article->setAuthorId(new UUID($inputValue));

        $this->assertEquals($expectedValue, (string)$article->getAuthorId());
    }

    /**
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     * @dataProvider CommentsProviderForMethodSetAndGetId
     */
    public function testCommentSetAndGetAuthorArticleId($inputValue, $expectedValue){
        $article = $this->getComment();
        $article->setArticleId(new UUID($inputValue));

        $this->assertEquals($expectedValue, (string)$article->getArticleId());
    }

    private function CommentsProviderForMethodSetAndGetText():iterable{
        return [ ["text1", "text1"],
            ["text2", "text2"],
            ["text3", "text3"]
        ];
    }

    /**
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     * @dataProvider CommentsProviderForMethodSetAndGetText
     */
    public function testArticleSetAndGetText($inputValue, $expectedValue){
        $article = $this->getComment();
        $article->setText($inputValue);

        $this->assertEquals($expectedValue, (string)$article->getText());
    }
}
