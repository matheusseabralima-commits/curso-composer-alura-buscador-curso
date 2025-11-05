<?php
declare(strict_types=1);
namespace Alura\Mvc\Modelo; // O endereço correto

class Video
{
    public function __construct(
        public readonly string $url,
        public readonly string $title,
        public readonly ?int $id = null,
        public ?string $file_path = null,
    ) {
    }
}