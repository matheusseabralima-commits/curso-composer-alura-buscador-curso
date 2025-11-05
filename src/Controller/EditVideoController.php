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
        // 1. Pegar dados do POST (PSR-7)
        $postData = $request->getParsedBody();
        $id = filter_var($postData['id'] ?? '', FILTER_VALIDATE_INT);
        $url = filter_var($postData['url'] ?? '', FILTER_SANITIZE_URL);
        $title = filter_var($postData['titulo'] ?? '', FILTER_SANITIZE_STRING);

        if ($id === false || $id === null || $url === false || $title === false) {
            return new Response(302, ['Location' => '/?sucesso=0']);
        }

        // 2. Buscar o vídeo *existente* (para sabermos o file_path antigo)
        $oldVideo = $this->videoRepository->find($id);
        if ($oldVideo === false) {
            return new Response(302, ['Location' => '/?sucesso=0']);
        }

        // 3. Criar a nova entidade Video com os dados atualizados
        // (Isso resolve o seu erro "readonly" da image_9e2b08.png)
        $video = new Video(url: $url, title: $title, id: $id);
        
        // 4. Lidar com o Upload de Imagem
        $files = $request->getUploadedFiles();
        /** @var ?UploadedFileInterface $uploadedImage */
        $uploadedImage = $files['image'] ?? null;

        if ($uploadedImage !== null && $uploadedImage->getError() === UPLOAD_ERR_OK) {
            // Se uma IMAGEM NOVA foi enviada
            $safeFileName = uniqid('upload_') . '_' . pathinfo($uploadedImage->getClientFilename(), PATHINFO_BASENAME);
            $targetPath = __DIR__ . '/../../public/img/uploads/' . $safeFileName;
            
            // (Bônus) Apagar a imagem antiga, se ela existir
            // ESTA É A LINHA QUE CORRIGIMOS (Linha 54)
            if ($oldVideo->file_path !== null && file_exists(__DIR__ . '/../../public/img/uploads/' . $oldVideo->file_path)) {
                unlink(__DIR__ . '/../../public/img/uploads/' . $oldVideo->file_path);
            }

            $uploadedImage->moveTo($targetPath);
            $video->file_path = $safeFileName; // Salva o nome do novo arquivo
        } else {
            // Se NENHUMA imagem nova foi enviada, mantenha a antiga.
            $video->file_path = $oldVideo->file_path;
        }

        // 5. Atualizar no banco
        $success = $this->videoRepository->update($video);

        if ($success === false) {
            return new Response(302, ['Location' => '/?sucesso=0']);
        } else {
            return new Response(302, ['Location' => '/?sucesso=1']); // Sucesso!
        }
    }
}