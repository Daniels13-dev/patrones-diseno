<?php

namespace App\Classes\Builders;

use App\Classes\Interfaces\PizzeriaInterface;

class PizzeriaBuilder implements PizzeriaInterface
{
    protected $pizza;

    protected function reset(): void
    {
        $pizza = '';
    }

    public function masa(string $masa): Pizzeria
    {
        $this->reset();
        $this->pizza = "Masa: " . $masa;
        return $this;
    }

    public function salsa(string $salsa, string $operator = ','): Pizzeria
    {
        $this->pizza .= "$operator con salsa de ". "'$salsa'";
        return $this;
    }

    public function queso(string $queso, string $operator = ','): Pizzeria
    {
        $this->pizza .= "$operator el queso es" . " '$queso'";
        return $this;
    }

    public function ingredientes(array $fields): Pizzeria
    {
        $this->pizza .= "$operator los ingredientes son" . "'$ingredientes'";
        return $this;
    }

    public function armarOrden(): string
    {   
        $pizza = $this->pizza;
        return $pizza;
    }
}