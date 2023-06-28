<?php

class DescargaArchivos
{
    protected $word;
    protected $pdf;

    public function __construct()
    {
        $this->word = new Word();
        $this->pdf = new Pdf();
    }

    public function descargarArchivo(string $url): void
    {
        $this->pdf->readPdf();
        echo "Guardando archivo...\n";
        echo "Descargado!\n";
    }
}

class Word
{
    public static function create(): Word { /* ... */ }

    public function saveAs(string $path): void { /* ... */ }
}

class Pdf
{
    public static function create(): Pdf { /* ... */ }

    public function readPdf(): void { 
        echo "hola\n";
    }

    public function saveAs(string $path): void { /* ... */ }
}

function clientCode(DescargaArchivos $facade)
{
    $facade->descargarArchivo("url");
}

$facade = new DescargaArchivos();
clientCode($facade);