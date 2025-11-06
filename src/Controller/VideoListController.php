<?php
declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VideoListController implements RequestHandlerInterface
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $videos = $this->videoRepository->all(); 

        ob_start();
        
        // Esta view precisa da variável $videos
        // Ela também deve incluir o 'inicio-html.php' e 'fim-html.php'
        require_once __DIR__ . '/../../views/video-list.php'; 
        
        $htmlBody = ob_get_clean();

        return new Response(200, [], $htmlBody);
    }
}