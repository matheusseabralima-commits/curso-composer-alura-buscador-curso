<?php
declare(strict_types=1);

namespace Alura\Mvc\Controller;

// 1. Conserta o "unknown class" (Erro 1 da imagem)
use Alura\Mvc\Modelo\Video; 
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\RequestHandlerInterface; // Conserta o "MiddlewareInterface" zumbi

class VideoEditController implements RequestHandlerInterface // Padrão CORRETO
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface // Método CORRETO
    {
        $postData = $request->getParsedBody();
        $id = filter_var($postData['id'] ?? '', FILTER_VALIDATE_INT);
        $url = filter_var($postData['url'] ?? '', FILTER_SANITIZE_URL);
        
        // No form, o name="titulo"
        $title = filter_var($postData['titulo'] ?? '', FILTER_SANITIZE_STRING);

        if ($id === false || $id === null || $url === false || $title === false) {
            return new Response(302, ['Location' => '/videos?sucesso=0']);
        }

        // Conserta o "findByID" (erro de 'image_373335.png')
        $oldVideo = $this->videoRepository->find($id); 
        if ($oldVideo === false) {
            return new Response(302, ['Location' => '/videos?sucesso=0']);
        }

        // 2. Conserta o construtor (Erro 2 da imagem)
        // Usa a "Planta" de 4 argumentos
        $video = new Video(url: $url, title: $title, id: $id);
        
        // 3. Lógica de Upload de Imagem
        $files = $request->getUploadedFiles();
        /** @var ?UploadedFileInterface $uploadedImage */
        $uploadedImage = $files['image'] ?? null;

        if ($uploadedImage !== null && $uploadedImage->getError() === UPLOAD_ERR_OK) {
            // Se uma IMAGEM NOVA foi enviada
            $safeFileName = uniqid('upload_') . '_' . pathinfo($uploadedImage->getClientFilename(), PATHINFO_BASENAME);
            $targetPath = __DIR__ . '/../../public/img/uploads/' . $safeFileName;
            
            if ($oldVideo->file_path !== null && file_exists(__DIR__ . '/../../public/img/uploads/' . $oldVideo->file_path)) {
                unlink(__DIR__ . '/../../public/img/uploads/' . $oldVideo->file_path);
            }

            $uploadedImage->moveTo($targetPath);
            $video->file_path = $safeFileName;
        } else {
            // Se NENHUMA imagem nova foi enviada, mantenha a antiga.
            $video->file_path = $oldVideo->file_path;
        }

        // 4. Conserta o 'update' (Erro 3 da imagem)
        // O 'update' do Repositório novo só aceita UM argumento
        $success = $this->videoRepository->update($video);

        if ($success === false) {
            return new Response(302, ['Location' => '/videos?sucesso=0']);
        } else {
            // Sucesso! Redireciona para a lista de vídeos
            return new Response(302, ['Location' => '/videos?sucesso=1']);
        }
    }}