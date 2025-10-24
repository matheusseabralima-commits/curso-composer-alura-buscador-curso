<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Repository\VideoRepository;
use Alura\Mvc\Entity\Video;

class VideoCreateController
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function create(): void
    {
        $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
        if ($url === false || $url === null) {
            header('Location: /?sucesso=0');
            exit();
        }

        $titulo = filter_input(INPUT_POST, 'titulo');
        if ($titulo === false || $titulo === null || trim($titulo) === '') {
            header('Location: /?sucesso=0');
            exit();
        }

        $video = new Video($url, $titulo);

        if ($this->videoRepository->add($video) === false) {
            header('Location: /?sucesso=0');
        } else {
            header('Location: /?sucesso=1');
        }
    }
}
