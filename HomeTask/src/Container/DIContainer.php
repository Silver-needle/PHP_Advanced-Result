<?php

namespace GB\HomeTask\Container;

use GB\HomeTask\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;

class DIContainer implements ContainerInterface
{
    // Массив правил создания объектов
    private array $resolvers = [];
    // Метод для добавления правил
    public function bind(string $type, $resolver)
    {
        $this->resolvers[$type] = $resolver;
    }

    /**
     * @throws NotFoundException
     */
    public function get(string $type): object
    {
        // Если есть правило для создания объекта типа $type,
        // (например, $type имеет значение
        // 'GB\.\.\UsersRepositoryInterface')
        if (array_key_exists($type, $this->resolvers)) {
        // .. тогда мы будем создавать объект того класса,
        // который указан в правиле
        // (например, 'GB\.\.\InMemoryUsersRepository')
            $typeToCreate = $this->resolvers[$type];

            // Если в контейнере для запрашиваемого типа
            // уже есть готовый объект — возвращаем его
            if (is_object($typeToCreate)) {
                return $typeToCreate;
            }
        // Вызываем тот же самый метод контейнера
        // и передаём в него имя класса, указанного в правиле
            return $this->get($typeToCreate);
        }
        if (!class_exists($type)) {
            throw new NotFoundException("Cannot resolve type: $type");
        }
        // Создаём объект рефлексии для запрашиваемого класса
        $reflectionClass = new ReflectionClass($type);
        // Исследуем конструктор класса
        $constructor = $reflectionClass->getConstructor();
        // Если конструктора нет -
        // просто создаём объект нужного класса
        if (null === $constructor) {
            return new $type();
        }
        // В этот массив мы будем собирать
        // объекты зависимостей класса
        $parameters = [];
        // Проходим по всем параметрам конструктора
        // (зависимостям класса)
        foreach ($constructor->getParameters() as $parameter) {
        // Узнаем тип параметра конструктора
        // (тип зависимости)
            $parameterType = $parameter->getType()->getName();
        // Получаем объект зависимости из контейнера
            $parameters[] = $this->get($parameterType);
        }
        // Создаём объект нужного нам типа
        // с параметрами
        return new $type(...$parameters);
    }

    public function has(string $id): bool
    {
        // TODO: Implement has() method.
    }

}
