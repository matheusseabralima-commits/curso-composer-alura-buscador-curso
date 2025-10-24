<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Repository\VideoRepository;
use Alura\Mvc\Entity\Video;

class VideoEditController
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function Edit(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id === false || $id === null) {
            header('Location: /?sucesso=0');
            exit();
        }

        // Valida URL e título
        $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
        $titulo = filter_input(INPUT_POST, 'titulo');

        if ($url === false || $titulo === false) {
            header('Location: /?sucesso=0');
            exit();
        }

        // Cria o objeto Video (id preenchido)
        $video = new Video($url, $titulo, $id);
        $repository = $this->videoRepository;

        // Atualiza no banco
        $sucesso = $repository->update($video);

        if ($sucesso) {
            header('Location: /?sucesso=1');
        } else {
            header('Location: /?sucesso=0');
        }
    }
}
