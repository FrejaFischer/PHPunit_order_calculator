<?php

class OrderCalculator 
{
    private float $unitPrice;
    private int $quantity;
    private float $taxRate;

    // 20% default tax rate
    public function __construct(float $unitPrice, int $quantity, float $taxRate = 0.2)
    {
        if ($unitPrice < 0 || $quantity < 0 || $taxRate < 0) {
            throw new InvalidArgumentException('Negative values are not allowed.');
        }
        
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
        $this->taxRate = $taxRate;
    }

    // Basic calculation: price before discounts
    private function calculateTotalPrice(): float 
    {
        return $this->unitPrice * $this->quantity;
    }

    // Apply bulk discounts
    // 10% discount for 10 items or more
    // 15% discount for 20 items or more
    private function applyBulkDiscount(): float 
    {
        $total = $this->calculateTotalPrice();

        if ($this->quantity >= 20) {
            return $total * 0.85;   // 15% discount
        } elseif ($this->quantity >= 10) {
            return $total * 0.90;   // 10% discount
        }        
        return $total; // No discount
    }

    // Apply tax
    private function applyTax(float $amount): float 
    {
        return $amount * (1 + $this->taxRate);
    }

    // Compute final price after applying both discount and tax
    public function calculateFinalPrice(): float 
    {
        $discountedPrice = $this->applyBulkDiscount();
        return round($this->applyTax($discountedPrice), 2);
    }
}