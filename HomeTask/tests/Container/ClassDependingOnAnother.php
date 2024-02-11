<?php
namespace GB\HomeTask\UnitTests\Container;
use GB\HomeTask\UnitTests\Container\SomeClassWithParametr;
use GB\HomeTask\UnitTests\Container\SomeClassWitOutDepend;

class ClassDependingOnAnother
{
    // Класс с двумя зависимостями
    public function __construct(
        private SomeClassWitOutDepend $one,
        private SomeClassWithParametr $two,
    )
    {}
    }
