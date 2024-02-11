<?php

use GB\HomeTask\Common\Arguments;
use GB\HomeTask\Exceptions\ArgumentsException;
use PHPUnit\Framework\TestCase;

class ArgumentsClassTest extends TestCase
{
    private function argumentProvider(){
        return [
            ['some_string', 'some_string'], // Тестовый набор
            [' some_string', 'some_string'], // Тестовый набор №2
            [' some_string ', 'some_string'],
            [123, '123'],
            [12.3, '12.3'],
        ];
    }

    /**
     * @throws ArgumentsException
     * @dataProvider argumentProvider
     */
    public function testItReturnsArgumentsValueByName($inputValue, $expectedValue){
        $arguments = new Arguments(["some_key"=>$inputValue]);

        $value = $arguments->getArg("some_key");

        $this->assertEquals($expectedValue,$value);
    }

    public function testArgumentException(){
        $arguments = new Arguments(["some_key"=>""]);
        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage("No such argument: some_key");

        $arguments->getArg("some_key");
    }

    private function argvProvider(){
        return [
            [['title=Geekbrains1'], ["title", "Geekbrains1"]], // Тестовый набор
            [['title=Geekbrains2'], ["title", "Geekbrains2"]], // Тестовый набор №2
            [['title=Geekbrains3'], ["title", "Geekbrains3"]],
        ];
    }

    /**
     * @param $inputValue
     * @param $expectedValue
     * @return void
     * @throws ArgumentsException
     * @dataProvider argvProvider
     */
    public function testArgumentFromArgv($inputValue, $expectedValue){
        $argument = Arguments::fromArgv($inputValue);

        $value = $argument->getArg($expectedValue[0]);

        $this->assertEquals($expectedValue[1], $value);
    }
}
