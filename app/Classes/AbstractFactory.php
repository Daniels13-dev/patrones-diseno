<?php

interface MuebleFactory
{
    public function Silla(): AbstractProductA;

    public function Mesa(): AbstractProductB;
}

class FabricaModerna implements MuebleFactory
{
    public function Silla(): AbstractProductA
    {
        return new SillaModerna();
    }

    public function Mesa(): AbstractProductB
    {
        return new MesaModerna();
    }
}

class FabricaAntigua implements MuebleFactory
{
    public function Silla(): AbstractProductA
    {
        return new SillaAntigua();
    }

    public function Mesa(): AbstractProductB
    {
        return new MesaAntigua();
    }
}

interface AbstractProductA
{
    public function usefulFunctionA(): string;
}

class SillaModerna implements AbstractProductA
{
    public function usefulFunctionA(): string
    {
        return "silla moderna";
    }
}

class SillaAntigua implements AbstractProductA
{
    public function usefulFunctionA(): string
    {
        return "silla antigua";
    }
}

interface AbstractProductB
{
    public function usefulFunctionB(): string;

    public function anotherUsefulFunctionB(AbstractProductA $collaborator): string;
}

class MesaModerna implements AbstractProductB
{
    public function usefulFunctionB(): string
    {
        return "El resultado es una mesa moderna";
    }

    public function anotherUsefulFunctionB(AbstractProductA $collaborator): string
    {
        $result = $collaborator->usefulFunctionA();

        return "El combo es de una mesa moderna con una {$result}";
    }
}

class MesaAntigua implements AbstractProductB
{
    public function usefulFunctionB(): string
    {
        return "El resultado es una mesa antigua";
    }

    public function anotherUsefulFunctionB(AbstractProductA $collaborator): string
    {
        $result = $collaborator->usefulFunctionA();

        return "El combo es de una mesa antigua con una {$result}";
    }
}

function clientCode(MuebleFactory $factory)
{
    $productA = $factory->Silla();
    $productB = $factory->Mesa();

    echo $productB->usefulFunctionB() . "\n";
    echo $productB->anotherUsefulFunctionB($productA) . "\n";
}

echo "Test primera fabrica\n";
clientCode(new FabricaModerna());

echo "\n";

echo "Test segunda fabrica\n";
clientCode(new FabricaAntigua());