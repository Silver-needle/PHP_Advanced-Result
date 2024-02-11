<?php

use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Common\Name;
use GB\HomeTask\Common\UUID;
use PHPUnit\Framework\TestCase;

class UserClassTest extends TestCase
{
    public function testConstructor():void{
        $arg = new User(new UUID("123e4567-e89b-12d3-a456-426614174000"),"admin1", "test", new Name("first_name1", "last_name1"));
        $value = (string)$arg;
        $this->assertEquals("id=123e4567-e89b-12d3-a456-426614174000, name=first_name1, lastname=last_name1, username=admin1",trim($value));
    }

    /**
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     */
    private function getUser(): User
    {
        return new User(UUID::random(),"admin", "test", new Name("first_name", "last_name"));
    }

    private function UsersProviderForMethodSetUserName():iterable{
        return [["admin1" , "admin1"],
            ["admin2","admin2"],
            ["admin3","admin3"]
        ];
    }

    /**
     * @return void
     * @dataProvider UsersProviderForMethodSetUserName
     */
    public function testSetUserName($inputValue, $expectedValue): void
    {
        $user = $this->getUser();
        $user->setUsername($inputValue);
        $this->assertEquals($expectedValue,$user->getUsername());

    }

    private function UsersProviderForMethodSetName():iterable{
        return [[ ["first_name1", "last_name1"], ["first_name1", "last_name1"]],
            [["first_name2", "last_name2"], ["first_name2", "last_name2"]],
            [["first_name3", "last_name3"], ["first_name3", "last_name3"]]
        ];
    }

    /**
     * @param $inputValue
     * @param $expectedValue
     * @return void
     * @dataProvider UsersProviderForMethodSetName
     */
    public function testSetName($inputValue, $expectedValue): void
    {   $user = $this->getUser();
        $name = new Name("test", "test");
        $name->setFirstName($inputValue[0]);
        $name->setLastName($inputValue[1]);

        $user->setName($name);
        $this->assertEquals($expectedValue[0],$user->getName()->getFirstName());
        $this->assertEquals($expectedValue[1],$user->getName()->getLastName());

    }

    private function UsersProviderForMethodSetId():iterable{
        return [ ["123e4567-e89b-12d3-a456-426614174111", "123e4567-e89b-12d3-a456-426614174111"],
            ["123e4567-e89b-12d3-a456-426614174222", "123e4567-e89b-12d3-a456-426614174222"],
            ["123e4567-e89b-12d3-a456-426614174333", "123e4567-e89b-12d3-a456-426614174333"]
        ];
    }

    /**
     * @param $inputValue
     * @param $expectedValue
     * @return void
     * @dataProvider UsersProviderForMethodSetId
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     */
    public function testSetId($inputValue, $expectedValue): void
    {
        $user = $this->getUser();
        $user->setId(new UUID($inputValue));
        $this->assertEquals($expectedValue, (string)$user->getId());
    }
}
