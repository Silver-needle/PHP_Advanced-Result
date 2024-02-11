<?php
namespace GB\HomeTask\UnitTests\Container;

class SomeClassWithParametr
{
    // Класс с одним параметром
    public function __construct(
        private int $value
    ) {
    }
    public function value(): int
    {
        return $this->value;
    }
}
