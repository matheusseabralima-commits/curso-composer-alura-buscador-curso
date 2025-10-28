<?php

declare(strict_types=1);

namespace Alura\Mvc\Entity;

class Video
{
    public readonly int $id;
    public readonly string $url;

    // 1. ADICIONAMOS A NOVA PROPRIEDADE (pode ser nula)
    public readonly ?string $image_path;

    public function __construct(
        string $url,
        public readonly string $title,
        ?string $image_path = null // 2. ADICIONAMOS O PARÂMETRO NO CONSTRUTOR
    ) {
        $this->setUrl($url);
        // 3. ATRIBUÍMOS O VALOR
        $this->image_path = $image_path;
    }

    private function setUrl(string $url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException();
        }

        $this->url = $url;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
