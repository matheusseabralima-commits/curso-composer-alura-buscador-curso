<?php
declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Modelo\Video;
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EditVideoController implements RequestHandlerInterface
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $postData = $request->getParsedBody();
        $id = filter_var($postData['id'] ?? '', FILTER_VALIDATE_INT);
        $url = filter_var($postData['url'] ?? '', FILTER_SANITIZE_URL);
        $title = filter_var($postData['titulo'] ?? '', FILTER_SANITIZE_STRING);

        if ($id === false || $id === null || $url === false || $title === false || empty($title) || empty($url)) {
            // CORREÇÃO: Usa a sessão para o erro
            $_SESSION['error_message'] = 'Dados inválidos para edição.';
            return new Response(302, ['Location' => '/videos']);
        }

        $oldVideo = $this->videoRepository->find($id);
        if ($oldVideo === false) {
            $_SESSION['error_message'] = 'Vídeo não encontrado para edição.';
            return new Response(302, ['Location' => '/videos']);
        }

        $video = new Video(url: $url, title: $title, id: $id);
        
        $files = $request->getUploadedFiles();
        /** @var ?UploadedFileInterface $uploadedImage */
        $uploadedImage = $files['image'] ?? null;

        if ($uploadedImage !== null && $uploadedImage->getError() === UPLOAD_ERR_OK) {
            // Uma IMAGEM NOVA foi enviada
            $safeFileName = uniqid('upload_') . '_' . pathinfo($uploadedImage->getClientFilename(), PATHINFO_BASENAME);
            $targetPath = __DIR__ . '/../../public/img/uploads/' . $safeFileName;
            
            try {
                $uploadedImage->moveTo($targetPath);
                $video->file_path = $safeFileName; // Salva o nome do novo arquivo

                // Apaga a imagem antiga, se ela existir
                if ($oldVideo->file_path !== null && file_exists(__DIR__ . '/../../public/img/uploads/' . $oldVideo->file_path)) {
                    unlink(__DIR__ . '/../../public/img/uploads/' . $oldVideo->file_path);
                }
            } catch (\RuntimeException $e) {
                $_SESSION['error_message'] = 'Erro ao salvar a nova imagem.';
                return new Response(302, ['Location' => '/videos']);
            }
        } else {
            // NENHUMA imagem nova foi enviada, mantenha a antiga.
            $video->file_path = $oldVideo->file_path;
        }

        $success = $this->videoRepository->update($video);

        if ($success === false) {
            // CORREÇÃO: Usa a sessão para o erro
            $_SESSION['error_message'] = 'Erro ao atualizar o vídeo.';
            return new Response(302, ['Location' => '/videos']);
        } else {
            // CORREÇÃO: Usa a sessão para o sucesso e redireciona para a lista
            $_SESSION['success_message'] = 'Vídeo atualizado com sucesso!';
            return new Response(302, ['Location' => '/videos']);
        }
    }
}