<?php

use GB\HomeTask\Container\DIContainer;
use GB\HomeTask\Exceptions\NotFoundException;
use GB\HomeTask\Repositories\Users\InMemoryUsersRepo;
use GB\HomeTask\Repositories\Users\UsersRepositoryInterface;
use GB\HomeTask\UnitTests\Container\ClassDependingOnAnother;
use GB\HomeTask\UnitTests\Container\SomeClassWithParametr;
use GB\HomeTask\UnitTests\Container\SomeClassWitOutDepend;
use PHPUnit\Framework\TestCase;

class DIContainerTest extends TestCase
{
    public function testItThrowsAnExceptionIfCannotResolveType(){
        $container = new DIContainer();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(
            'Cannot resolve type: SomeClass1'
        );

        $container->get(SomeClass1::class);

    }

    public function testItResolvesClassWithoutDependencies(): void
    {
        // Создаём объект контейнера
        $container = new DIContainer();
        // Пытаемся получить объект класса без зависимостей
        $object = $container->get(SomeClassWitOutDepend::class);
        // Проверяем, что объект, который вернул контейнер,
        // имеет желаемый тип
        $this->assertInstanceOf(
            SomeClassWitOutDepend::class,
            $object
        );
    }

    public function testItResolvesClassByContract(): void
    {
        // Создаём объект контейнера
        $container = new DIContainer();
        // Устанавливаем правило, по которому
        // всякий раз, когда контейнеру нужно
        // создать объект, реализующий контракт
        // UsersRepositoryInterface, он возвращал бы
        // объект класса InMemoryUsersRepository
        $container->bind(
            UsersRepositoryInterface::class,
            InMemoryUsersRepo::class
        );
        // Пытаемся получить объект класса,
        // реализующего контракт UsersRepositoryInterface
        $object = $container->get(UsersRepositoryInterface::class);
        // Проверяем, что контейнер вернул
        // объект класса InMemoryUsersRepository
        $this->assertInstanceOf(
            InMemoryUsersRepo::class,
            $object
        );
    }

    public function testItReturnsPredefinedObject(): void
    {
        // Создаём объект контейнера
        $container = new DIContainer();
        // Устанавливаем правило, по которому
        // всякий раз, когда контейнеру нужно
        // вернуть объект типа SomeClassWithParameter,
        // он возвращал бы предопределённый объект
        $container->bind(
            SomeClassWithParametr::class,
            new SomeClassWithParametr(42)
        );
        // Пытаемся получить объект типа SomeClassWithParameter
        $object = $container->get(SomeClassWithParametr::class);
        // Проверяем, что контейнер вернул
        // объект того же типа
        $this->assertInstanceOf(
            SomeClassWithParametr::class,
            $object
        );
        // Проверяем, что контейнер вернул
        // тот же самый объект
        $this->assertSame(42, $object->value());
    }

    public function testItResolvesClassWithDependencies(): void
    {
        // Создаём объект контейнера
        $container = new DIContainer();
        // Устанавливаем правило получения
        // объекта типа SomeClassWithParameter
        $container->bind(
            SomeClassWithParametr::class,
            new SomeClassWithParametr(42)
        );
        // Пытаемся получить объект типа ClassDependingOnAnother
        $object = $container->get(ClassDependingOnAnother::class);
        // Проверяем, что контейнер вернул
        // объект нужного нам типа
        $this->assertInstanceOf(
            ClassDependingOnAnother::class,
            $object
        );
    }
}
