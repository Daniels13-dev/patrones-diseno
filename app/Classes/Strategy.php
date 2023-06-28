<?php

class OrderController
{
    public function post(string $url, array $data)
    {
        echo "Controller: Peticion Post $url con " . json_encode($data) . "\n";

        $path = parse_url($url, PHP_URL_PATH);

        if (preg_match('#^/orders?$#', $path, $matches)) {
            $this->postNewOrder($data);
        } else {
            echo "Controller: 404 page\n";
        }
    }

    public function get(string $url): void
    {
        echo "Controller: Peticion Get $url\n";

        $path = parse_url($url, PHP_URL_PATH);
        $query = parse_url($url, PHP_URL_QUERY);
        parse_str($query, $data);

        if (preg_match('#^/orders?$#', $path, $matches)) {
            $this->getAllOrders();
        } elseif (preg_match('#^/order/([0-9]+?)/payment/([a-z]+?)(/return)?$#', $path, $matches)) {
            $order = Order::get($matches[1]);

            $paymentMethod = PaymentFactory::getPaymentMethod($matches[2]);

            if (!isset($matches[3])) {
                $this->getPayment($paymentMethod, $order, $data);
            } else {
                $this->getPaymentReturn($paymentMethod, $order, $data);
            }
        } else {
            echo "Controller: 404 page\n";
        }
    }

    public function postNewOrder(array $data): void
    {
        $order = new Order($data);
        echo "Controller: Orden creada #{$order->id}.\n";
    }

    public function getAllOrders(): void
    {
        echo "Controller: Aqui estan sus ordenes:\n";
        foreach (Order::get() as $order) {
            echo json_encode($order, JSON_PRETTY_PRINT) . "\n";
        }
    }

    public function getPayment(PaymentMethod $method, Order $order, array $data): void
    {
        $form = $method->getPaymentForm($order);
        echo "Controller: Estos son los metodos de pago:\n";
        echo $form . "\n";
    }

    public function getPaymentReturn(PaymentMethod $method, Order $order, array $data): void
    {
        try {
            if ($method->validateReturn($order, $data)) {
                echo "Controller: Gracias por su orden!\n";
                $order->complete();
            }
        } catch (\Exception $e) {
            echo "Controller: got an exception (" . $e->getMessage() . ")\n";
        }
    }
}

class Order
{
    private static $orders = [];

    public static function get(int $orderId = null)
    {
        if ($orderId === null) {
            return static::$orders;
        } else {
            return static::$orders[$orderId];
        }
    }

    public function __construct(array $attributes)
    {
        $this->id = count(static::$orders);
        $this->status = "new";
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }
        static::$orders[$this->id] = $this;
    }

    public function complete(): void
    {
        $this->status = "completa";
        echo "Orden: #{$this->id} esta {$this->status}.";
    }
}

class PaymentFactory
{
    public static function getPaymentMethod(string $id): PaymentMethod
    {
        switch ($id) {
            case "cc":
                return new CreditCardPayment();
            case "paypal":
                return new PayPalPayment();
            default:
                throw new \Exception("Unknown Payment Method");
        }
    }
}

interface PaymentMethod
{
    public function getPaymentForm(Order $order): string;

    public function validateReturn(Order $order, array $data): bool;
}

class CreditCardPayment implements PaymentMethod
{
    static private $store_secret_key = "swordfish";

    public function getPaymentForm(Order $order): string
    {
        $returnURL = "https://our-website.com/" .
            "order/{$order->id}/payment/cc/return";

        return <<<FORM
<form action="https://my-credit-card-processor.com/charge" method="POST">
    <input type="hidden" id="email" value="{$order->email}">
    <input type="hidden" id="total" value="{$order->total}">
    <input type="hidden" id="returnURL" value="$returnURL">
    <input type="text" id="cardholder-name">
    <input type="text" id="credit-card">
    <input type="text" id="expiration-date">
    <input type="text" id="ccv-number">
    <input type="submit" value="Pay">
</form>
FORM;
    }

    public function validateReturn(Order $order, array $data): bool
    {
        echo "Pago por tarjeta de credito: ...validando... ";

        if ($data['key'] != md5($order->id . static::$store_secret_key)) {
            throw new \Exception("Payment key is wrong.");
        }

        if (!isset($data['success']) || !$data['success'] || $data['success'] == 'false') {
            throw new \Exception("Payment failed.");
        }

        if (floatval($data['total']) < $order->total) {
            throw new \Exception("Payment amount is wrong.");
        }

        echo "Hecho!\n";

        return true;
    }
}

class PayPalPayment implements PaymentMethod
{
    public function getPaymentForm(Order $order): string
    {
        $returnURL = "https://our-website.com/" .
            "order/{$order->id}/payment/paypal/return";

        return $returnURL;
    }

    public function validateReturn(Order $order, array $data): bool
    {
        echo "Pago por PayPal: ...validando... ";

        echo "Hecho!\n";

        return true;
    }
}

$controller = new OrderController();

echo "Cliente: creacion de ordenes\n";

$controller->post("/orders", [
    "email" => "daniel.sanchez@licitaciones.info",
    "producto" => "Cafe",
    "total" => 10000,
]);

$controller->post("/orders", [
    "email" => "daniel.ds.1302@gmail.com",
    "producto" => "Chocolate",
    "total" => 15000,
]);

echo "\nCliente: Puedes listar mis ordenes, por favor:\n";

$controller->get("/orders");

echo "\nCliente: Me gustaria pagar con el segundo\n";

$controller->get("/order/1/payment/paypal");

$controller->get("/order/1/payment/paypal/return" .
    "?key=c55a3964833a4b0fa4469ea94a057152&success=true&total=19.95");