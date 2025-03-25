<?php

require_once 'classes/OrderCalculator.php';

use PHPUnit\Framework\TestCase;

Use PHPUnit\Framework\Attributes\DataProvider;


class calculatorTest extends TestCase
{
    /*
        Test for passes
    */
    #[DataProvider('CalculatePasses')]

    public function testCalculatePasses(float $unitPrice, int $quantity, float $expected): void
    {
        $calculator = new OrderCalculator($unitPrice, $quantity);

        $result = $calculator->calculateFinalPrice();

        $this->assertEquals($expected, $result);
    }

    public static function CalculatePasses(): array
    {
        return [
            [100,2,240],
            [99.99,4,479.95], // with decimals
            [0,4,0], // with zero
            [10,10,108], // discount for 10
            [10,21,214.2] // discount for over 20
        ];
    }

    /*
        Test for fails
    */

    #[DataProvider('CalculateFails')]

    public function testCalculateFails(float $unitPrice, int $quantity, float $expected): void
    {
        $calculator = new OrderCalculator($unitPrice, $quantity);

        $result = $calculator->calculateFinalPrice();

        $this->assertNotEquals($expected, $result);
    }

    public static function CalculateFails(): array
    {
        return [
            [100,2,200] // wrong result without taxes
        ];
    }

    /*
        If any parameter is below zero, the application should raise an exception
    */
    #[DataProvider('RaiseException')]

    public function testExceptionInvalidSystem(float $unitPrice, int $quantity): void 
    {
        $this->expectException(InvalidArgumentException::class);
        $calculator = new OrderCalculator($unitPrice, $quantity);
    }

    public static function RaiseException(): array
    {
        return [
            [-1,2], // negative unitprice
            [1,-2] // negative quantity
        ];
    }
}