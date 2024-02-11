<?php

use GB\HomeTask\Blog\User\User;
use GB\HomeTask\Common\Name;
use GB\HomeTask\Common\UUID;
use GB\HomeTask\Exceptions\UserNotFoundException;
use GB\HomeTask\Repositories\Users\SqLiteUserRepo;
use GB\HomeTask\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;

class UserRepoTest extends TestCase
{
    public function testItSavesUserToDatabase():void{
        $connectionMock = $this->createStub(PDO::class);
        $statementMock = $this->createStub(PDOStatement::class);

        $connectionMock->method("prepare")->willReturn($statementMock);

        $statementMock->expects($this->once())->method('execute')->with([ // Даём понять, что метод execute вызовется лишь 1 раз с единственным аргументом - массивом
            ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
            ':username' => 'ivan123',
            ':first_name' => 'Ivan',
            ':last_name' => 'Ivanov',
        ])->willReturn(true);

        $sqlRepo = new SqLiteUserRepo($connectionMock, new DummyLogger());
        // Свойства пользователя точно такие,как и в описании мока
        $sqlRepo->save(new User(
            new UUID('123e4567-e89b-12d3-a456-426614174000'),
            'ivan123',
            new Name('Ivan', 'Ivanov'))
        );
    }

    /**
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     */
    public function testExceptionWhenUserNotFound(){
        $connectionMock = $this->createStub(PDO::class);
        $statementMock = $this->createStub(PDOStatement::class);

        $connectionMock->method('prepare')->willReturn($statementMock);

        $statementMock->expects($this->once())->method('execute')->with(
            [ // Даём понятьь что метод execute вызовется лишь 1 раз с единственным аргументом - массивом
            ':uuid' => '123e4567-e89b-12d3-a456-426614174000']
        );
        $statementMock->method('fetch')->willReturn(false);

        $sqlRepo=new SqLiteUserRepo($connectionMock, new DummyLogger());

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage("Cannot find user: 123e4567-e89b-12d3-a456-426614174000");

        $sqlRepo->get(new UUID('123e4567-e89b-12d3-a456-426614174000'));
    }

    /**
     * @throws \GB\HomeTask\Exceptions\InvalidArgumentException
     */
    public function testGetUserByUserName(){
        $connectionMock = $this->createStub(PDO::class);
        $statementMock = $this->createStub(PDOStatement::class);

        $connectionMock->method('prepare')->willReturn($statementMock);

        $statementMock->expects($this->once())->method('execute')->with(
            [ // Даём понять, что метод execute вызовется лишь 1 раз с единственным аргументом - массивом
                ':username' => 'ivan228']
        );
        $statementMock->method('fetch')->willReturn([
            'uuid'=>'123e4567-e89b-12d3-a456-426614174000',
            'username'=>'ivan228',
            'first_name'=>'Ivan',
            'last_name'=> 'Ivanov'
            ]);

        $sqlRepo=new SqLiteUserRepo($connectionMock, new DummyLogger());

        $user = new User(new UUID('123e4567-e89b-12d3-a456-426614174000'), 'ivan228', new Name('Ivan', 'Ivanov'));

        $value = $sqlRepo->getByUsername('ivan228');

        $this->assertEquals($user, $value);
    }
}
