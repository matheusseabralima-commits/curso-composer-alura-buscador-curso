<?php
declare(strict_types=1);
namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Helper\FlashMessageTrait;
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

class NewVideoController implements Controller
{
    use FlashMessageTrait;

    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function processaRequisicao(ServerRequestInterface $request): ResponseInterface
    {
        // Pegamos os dados do corpo da requisição (POST)
        $requestBody = $request->getParsedBody();
        $url = filter_var($requestBody['url'] ?? '', FILTER_VALIDATE_URL);
        if ($url === false) {
            $this->addErrorMessage('URL inválida');
            return new Response(302, ['Location' => '/novo-video']);
        }
        $titulo = filter_var($requestBody['titulo'] ?? '');
        if ($titulo === false) {
            $this->addErrorMessage('Título não informado');
            return new Response(302, ['Location' => '/novo-video']);
        }
        $video = new Video($url, $titulo);

        // Pegamos os arquivos enviados via PSR-7
        $uploadedFiles = $request->getUploadedFiles();
        /** @var UploadedFileInterface $image */
        $image = $uploadedFiles['image'] ?? null;

        if ($image && $image->getError() === UPLOAD_ERR_OK) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $tmpFile = $image->getStream()->getMetadata('uri');
            $mimeType = $finfo->file($tmpFile);

            if (str_starts_with($mimeType, 'image/')) {
                $safeFileName = uniqid('upload_') . '_' . pathinfo($image->getClientFilename(), PATHINFO_BASENAME);
                
                // Usamos o moveTo da PSR-7
                $image->moveTo(__DIR__ . '/../../public/img/uploads/' . $safeFileName);
                $video->file_path = $safeFileName;
            }
        }

        $success = $this->videoRepository->add($video);
        if ($success === false) {
            $this->addErrorMessage('Erro ao cadastrar vídeo');
            return new Response(302, ['Location' => '/novo-video']);
        } else {
            // Trocamos header() por 'return new Response()'
            return new Response(302, ['Location' => '/?sucesso=1']);
        }
    }
}