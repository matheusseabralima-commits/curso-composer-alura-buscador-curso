<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Repository\VideoRepository;

class VideoRemoveController
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function deleteVideo(int $id): bool
    {
     $sucesso = $this->videoRepository->remove($id);

    if ($sucesso) {
        header('Location: /?sucesso=1');
    } else {
        header('Location: /?sucesso=0');
    }

    exit();
    }
}
