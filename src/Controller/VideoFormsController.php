<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Repository\VideoRepository;

class VideoFormsController
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function showForm(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $video = [
            'url' => '',
            'title' => '',
        ];

        if ($id !== false && $id !== null) {
            $foundVideo = $this->videoRepository->findById($id);

            if ($foundVideo) {
                $video = [
                    'url' => $foundVideo->url,
                    'title' => $foundVideo->title,
                ];
            }
        }

        require_once __DIR__ . '/../../views/video-form.php';
    }
}