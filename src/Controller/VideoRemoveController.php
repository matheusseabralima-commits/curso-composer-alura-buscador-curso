<?php
declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Modelo\Video; // Importa a "Planta" correta
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface; // Padrão CORRETO

class VideoRemoveController implements RequestHandlerInterface // Padrão CORRETO
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface // Método CORRETO
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams['id'] ?? '', FILTER_VALIDATE_INT);

        if ($id === null || $id === false) {
            return new Response(302, ['Location' => '/videos?sucesso=0']);
        }
        
        // Chama o 'find()' (o método "novo")
        $video = $this->videoRepository->find($id); 
        $success = $this->videoRepository->remove($id);

        if ($success === false) {
            return new Response(302, ['Location' => '/videos?sucesso=0']);
        }

        // Bônus: Deleta a imagem da capa
        if ($video !== false && $video->file_path !== null) {
            $imagePath = __DIR__ . '/../../public/img/uploads/' . $video->file_path;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        // Redireciona para a lista de vídeos nova
        return new Response(302, ['Location' => '/videos?sucesso=1']);
    }
}