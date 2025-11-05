<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Modelo\Video; // Importante
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface; // <-- 1. Mude para a interface correta
class VideoFormsController implements RequestHandlerInterface
{
    public function __construct(private VideoRepository $repository)
    {
    }

    // 3. Renomeie para 'handle' e receba a Request PSR-7
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // 4. Pegue o 'id' dos parâmetros da query (jeito PSR-7)
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams['id'] ?? null, FILTER_VALIDATE_INT);

        // 5. Crie um objeto Video "vazio" para o formulário
        // (Assumindo que seu Video.php tem o construtor que eu sugeri antes)
        $video = new Video(url: '', title: ''); 

        if ($id !== false && $id !== null) {
            // 'findByID()' é o método padrão do repositório
            $foundVideo = $this->repository->find($id); 
            if ($foundVideo) {
                // Se encontrou, substitui o vídeo "vazio" pelo vídeo do banco
                $video = $foundVideo;
            }
        }

        // Renderiza a view
        ob_start();
        
        // Agora, o $video (objeto) é passado para a view
        require __DIR__ . '/../../views/video-form.php'; 
        
        $html = ob_get_clean();

        return new Response(200, ['Content-Type' => 'text/html'], $html);
    }
}