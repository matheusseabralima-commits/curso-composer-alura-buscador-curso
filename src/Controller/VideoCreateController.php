<?php
declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Modelo\Video;
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\UploadedFileInterface;

class VideoCreateController implements RequestHandlerInterface
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $postData = $request->getParsedBody();
        $url = filter_var($postData['url'] ?? '', FILTER_SANITIZE_URL);
        $title = filter_var($postData['titulo'] ?? '', FILTER_SANITIZE_STRING);

        if ($url === false || $title === false || empty($title) || empty($url)) {
            // CORREÇÃO: Usa a sessão para o erro
            $_SESSION['error_message'] = 'Todos os campos (URL e Título) são obrigatórios.';
            return new Response(302, ['Location' => '/novo-video']);
        }

        $video = new Video(url: $url, title: $title);
        
        $files = $request->getUploadedFiles();
        /** @var ?UploadedFileInterface $uploadedImage */
        $uploadedImage = $files['image'] ?? null;

        if ($uploadedImage !== null && $uploadedImage->getError() === UPLOAD_ERR_OK) {
            $safeFileName = uniqid('upload_') . '_' . pathinfo(
                $uploadedImage->getClientFilename(),
                PATHINFO_BASENAME
            );
            
            $targetPath = __DIR__ . '/../../public/img/uploads/' . $safeFileName;

            try {
                // Tenta mover o arquivo
                $uploadedImage->moveTo($targetPath);
                $video->file_path = $safeFileName; // Salva só o nome do arquivo
            } catch (\RuntimeException $e) {
                // Se falhar, define o erro e redireciona
                $_SESSION['error_message'] = 'Erro ao salvar a imagem. Verifique permissões da pasta.';
                return new Response(302, ['Location' => '/novo-video']);
            }
        }

        $success = $this->videoRepository->add($video);

        if ($success === false) {
            // CORREÇÃO: Usa a sessão para o erro
            $_SESSION['error_message'] = 'Erro ao cadastrar o vídeo. Tente novamente.';
            return new Response(302, ['Location' => '/novo-video']);
        } else {
            // CORREÇÃO: Usa a sessão para o sucesso e redireciona para a lista
            $_SESSION['success_message'] = 'Vídeo cadastrado com sucesso!';
            return new Response(302, ['Location' => '/videos']);
        }
    }
}