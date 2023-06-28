<?php

interface Pizzeria
{
    public function masa(string $masa): Pizzeria;

    public function salsa(string $salsa, string $operator = ','): Pizzeria;

    public function queso(string $queso, string $operator = ','): Pizzeria;

    public function ingredientes(array $ingredientes, string $operator = ','): Pizzeria;

    public function armarOrden(): string;
}