<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JsonVideoListController implements RequestHandlerInterface
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $videos = $this->videoRepository->all();

        $videoList = array_map(function (Video $video): array {
            return [
                // CORREÇÃO: Usar a propriedade pública correta
                'url' => $video->url,
                
                // CORREÇÃO: Usar a propriedade pública correta
                'title' => $video->title,
                
                // CORREÇÃO: Usar a propriedade pública correta (file_path)
                'file_path' => $video->file_path === null
                    ? null
                    : '/img/uploads/' . $video->file_path,
            ];
        }, array: $videos);

        return new Response(
            status: 200,
            headers: ['Content-Type' => 'application/json'],
            body: json_encode(value: $videoList)
        );
    }
}