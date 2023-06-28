<?php

interface Notificacion
{
    public function enviarNotificacion(string $text): string;
}

class TextInput implements Notificacion
{
    public function enviarNotificacion(string $text): string
    {
        return $text;
    }
}

class envio implements Notificacion
{
    protected $Notificacion;

    public function __construct(Notificacion $Notificacion)
    {
        $this->Notificacion = $Notificacion;
    }

    public function enviarNotificacion(string $text): string
    {
        return $this->Notificacion->enviarNotificacion($text);
    }
}

class notificacionSlack extends envio
{
    public function enviarNotificacion(string $text): string
    {
        $text = parent::enviarNotificacion('Notificacion de Slack enviada');
        return $text;
    }
}

class notificacionWhatsApp extends envio
{
    public function enviarNotificacion(string $text): string
    {
        $text = parent::enviarNotificacion('Notificacion de WhatsApp enviada');
        return $text;
    }
}

class notificacionInstagram extends envio
{
    public function enviarNotificacion(string $text): string
    {
        $text = parent::enviarNotificacion('Notificacion de Instagram enviada');
        return $text;
    }
}

function impresion(Notificacion $format, string $text)
{
    echo $format->enviarNotificacion($text);
}

$cabecera = <<<HERE
¡Notificacion!
HERE;

$naiveInput = new TextInput();
echo "----------\n";
impresion($naiveInput, $cabecera);
echo "\n\n\n";

$filteredInput = new notificacionSlack($naiveInput);
impresion($filteredInput, $cabecera);
echo "\n\n";

$naiveInput = new TextInput();
echo "----------\n";
impresion($naiveInput, $cabecera);
echo "\n\n\n";

$filteredInput = new notificacionWhatsApp($naiveInput);
impresion($filteredInput, $cabecera);
echo "\n\n";

$naiveInput = new TextInput();
echo "----------\n";
impresion($naiveInput, $cabecera);
echo "\n\n\n";

$filteredInput = new notificacionInstagram($naiveInput);
impresion($filteredInput, $cabecera);
echo "\n\n";
