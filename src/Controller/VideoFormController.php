<?php
declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Modelo\Video; // <-- Importa a planta correta
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface; 

class VideoFormController implements RequestHandlerInterface
{
    public function __construct(private VideoRepository $repository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams['id'] ?? null, FILTER_VALIDATE_INT);

        $video = new Video(url: '', title: ''); 

        if ($id !== false && $id !== null) {
            
            // ⬇️ CORREÇÃO (de 'findByID' para 'find') ⬇️
            $foundVideo = $this->repository->find($id); 
            
            if ($foundVideo) {
                $video = $foundVideo;
            }
        }

        ob_start();
        require __DIR__ . '/../../views/video-form.php'; 
        $html = ob_get_clean();

        return new Response(200, ['Content-Type' => 'text/html'], $html);
    }
}