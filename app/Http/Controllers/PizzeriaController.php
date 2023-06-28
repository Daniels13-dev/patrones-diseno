<?php

namespace App\Http\Controllers;

use App\Classes\Interfaces\PizzeriaInterface;

class PizzeriaController extends Controller
{
    public static function getOrden() {
        $pizzeria = new PizzeriaInterface();
        $pizza = $pizzeria
            ->masa('crujiente')
            ->salsa('napolitana')
            ->queso('mozzarella')
            ->ingredientes(['pepperoni', 'salami'])
            ->armarOrden();
        echo $pizza;
    }
}