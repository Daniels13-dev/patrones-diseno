<?php 

namespace App\Classes\Factories;

use App\Classes\Builders\PizzeriaBuilder;

class PizzeriaFactory {
    
    public static function orden($masa, $salsa, $queso, $ingredientes) : PizzeriaBuilder {
        return new PizzeriaBuilder($masa, $salsa, $queso, $ingredientes);
    } 
}