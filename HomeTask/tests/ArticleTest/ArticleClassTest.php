<?php

use GB\HomeTask\Blog\Article\Article;
use GB\HomeTask\Common\UUID;
use PHPUnit\Framework\TestCase;

class ArticleClassTest extends TestCase
{
    public function testConstructor():void{
        $arg = new Article(new UUID("123e4567-e89b-12d3-a456-426614174000"), 
        new UUID("123e4567-e89b-12d3-a456-426614174001"), "test_title", "test_text");
        $value = (string)$arg;
        $this->assertEquals("id=123e4567-e89b-12d3-a456-426614174000, 
        authorId=123e4567-e89b-12d3-a456-426614174001, title=test_title, text=test_text",trim($value));
    }

    /**
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     */
    private function getArticle(): Article
    {
        return new Article(UUID::random(),UUID::random(),"test", "text" );
    }

    private function ArticlesProviderForMethodSetAndGetId():iterable{
        return [ ["123e4567-e89b-12d3-a456-426614174111", "123e4567-e89b-12d3-a456-426614174111"],
            ["123e4567-e89b-12d3-a456-426614174222", "123e4567-e89b-12d3-a456-426614174222"],
            ["123e4567-e89b-12d3-a456-426614174333", "123e4567-e89b-12d3-a456-426614174333"]
        ];
    }

    /**
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     * @dataProvider ArticlesProviderForMethodSetAndGetId
     */
    public function testArticleSetAndGetId($inputValue, $expectedValue){
        $article = $this->getArticle();
        $article->setId(new UUID($inputValue));

        $this->assertEquals($expectedValue, (string)$article->getId());
    }

    private function ArticlesProviderForMethodSetAndGetAuthorId():iterable{
        return [ ["123e4567-e89b-12d3-a456-426614174111", "123e4567-e89b-12d3-a456-426614174111"],
            ["123e4567-e89b-12d3-a456-426614174222", "123e4567-e89b-12d3-a456-426614174222"],
            ["123e4567-e89b-12d3-a456-426614174333", "123e4567-e89b-12d3-a456-426614174333"]
        ];
    }

    /**
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     * @dataProvider ArticlesProviderForMethodSetAndGetAuthorId
     */
    public function testArticleSetAndGetAuthorId($inputValue, $expectedValue){
        $article = $this->getArticle();
        $article->setAuthorId(new UUID($inputValue));

        $this->assertEquals($expectedValue, (string)$article->getAuthorId());
    }

    private function ArticlesProviderForMethodSetAndGetTitle():iterable{
        return [ ["title1", "title1"],
            ["title2", "title2"],
            ["title3", "title3"]
        ];
    }

    /**
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     * @dataProvider ArticlesProviderForMethodSetAndGetTitle
     */
    public function testArticleSetAndGetTitle($inputValue, $expectedValue){
        $article = $this->getArticle();
        $article->setTitle($inputValue);

        $this->assertEquals($expectedValue, (string)$article->getTitle());
    }

    private function ArticlesProviderForMethodSetAndGetText():iterable{
        return [ ["text1", "text1"],
            ["text2", "text2"],
            ["text3", "text3"]
        ];
    }

    /**
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     * @dataProvider ArticlesProviderForMethodSetAndGetText
     */
    public function testArticleSetAndGetText($inputValue, $expectedValue){
        $article = $this->getArticle();
        $article->setText($inputValue);

        $this->assertEquals($expectedValue, (string)$article->getText());
    }
}
