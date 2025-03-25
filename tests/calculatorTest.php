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

    public function testCalculatePasses(float $unitPrice, int $quantity, float $taxRate, float $expected): void
    {
        $calculator = new OrderCalculator($unitPrice, $quantity, $taxRate);

        $result = $calculator->calculateFinalPrice();

        $this->assertEquals($expected, $result);
    }

    public static function CalculatePasses(): array
    {
        return [
            [100, 1, 0.2, 120], // One product, no discount
            [100, 2, 0, 200], // No tax rate
            [100, 2, 0.35, 270], // A different tax rate
            [99.99, 4, 0.2, 479.95], // Price with decimals
            [0, 4, 0.2, 0], // Price is zero
            [10, 10, 0.2, 108], // Minimum quantity for 10% discount
            [10, 19, 0.2, 205.2], // Maximum quantity for 10% discount
            [10, 20, 0.2, 204], // Minimum quantity for 15% discount
            [10, 250, 0.2, 2550], // Hundres of products, 15% discount
            [10, 1150, 0.2, 11730], // Thousands of products, 15% discount
        ];
    }

    /*
        Test for fails
    */

    #[DataProvider('CalculateFails')]

    public function testCalculateFails(float $unitPrice, int $quantity, float $taxRate, float $expected): void
    {
        $calculator = new OrderCalculator($unitPrice, $quantity, $taxRate);

        $result = $calculator->calculateFinalPrice();

        $this->assertNotEquals($expected, $result);
    }

    public static function CalculateFails(): array
    {
        return [
            [100,2,0.2,200] // wrong result without taxes
        ];
    }

    /*
        If any parameter is below zero, the application should raise an exception
    */
    #[DataProvider('RaiseException')]

    public function testExceptionInvalidSystem(float $unitPrice, int $quantity, float $taxRate): void 
    {
        $this->expectException(InvalidArgumentException::class);
        $calculator = new OrderCalculator($unitPrice, $quantity, $taxRate);
    }

    public static function RaiseException(): array
    {
        return [
            [-1, 2, 0.2], // negative unitprice
            [1, -2, 0.2], // negative quantity
            [10, 2, -0.2] // negative tax rate
        ];
    }
}