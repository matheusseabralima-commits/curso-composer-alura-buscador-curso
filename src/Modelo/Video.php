<?php

// Define um "endereço" ou "sobrenome" para a sua classe,
// para evitar conflito com outras classes que possam ter o mesmo nome.
// Isso é fundamental para o autoload do Composer funcionar.
namespace Alura\Play;

class Video
{
    // "readonly" significa que, uma vez que o objeto é criado,
    // essas propriedades não podem ser alteradas. Isso torna o código mais seguro.
    public readonly ?int $id;
    public readonly string $url;
    public string $title; // Deixamos o title editável para a página de edição

    public function __construct(?int $id, string $url, string $title)
    {
        $this->id = $id;
        $this->url = $url;
        $this->title = $title;
    }

    // Método para permitir a alteração do título
    public function setTitle(string $newTitle): void
    {
        $this->title = $newTitle;
    }
}