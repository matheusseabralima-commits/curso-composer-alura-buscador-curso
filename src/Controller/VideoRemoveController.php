<?php
declare(strict_types=1);

namespace Alura\Mvc\Controller;

// Não precisamos do 'use Video', pois este controller não cria um
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VideoRemoveController implements RequestHandlerInterface
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams['id'] ?? '', FILTER_VALIDATE_INT);

        if ($id === null || $id === false) {
            // CORREÇÃO: Usa a sessão para o erro
            $_SESSION['error_message'] = 'ID de vídeo inválido para remoção.';
            return new Response(302, ['Location' => '/videos']);
        }
        
        // 1. Busca o vídeo ANTES de deletar para pegar o file_path
        $video = $this->videoRepository->find($id); 
        
        // 2. Deleta do banco
        $success = $this->videoRepository->remove($id);

        if ($success === false) {
            // CORREÇÃO: Usa a sessão para o erro
            $_SESSION['error_message'] = 'Erro ao remover o vídeo. Tente novamente.';
            return new Response(302, ['Location' => '/videos']);
        }

        // 3. Deleta a imagem da capa, se tudo deu certo
        if ($video !== false && $video->file_path !== null) {
            $imagePath = __DIR__ . '/../../public/img/uploads/' . $video->file_path;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        // CORREÇÃO: Usa a sessão para o sucesso
        $_SESSION['success_message'] = 'Vídeo removido com sucesso!';
        return new Response(302, ['Location' => '/videos']);
    }
}