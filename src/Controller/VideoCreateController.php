<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

// 1. ⬇️ A CORREÇÃO MÁGICA ⬇️
// Agora ele sabe "onde" está a planta Video.
use Alura\Mvc\Modelo\Video; 
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface; // Padrão PSR-15
use Psr\Http\Message\UploadedFileInterface; // Para lidar com arquivos

class VideoCreateController implements RequestHandlerInterface // Padrão PSR-15
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // 2. Pegar dados do POST
        $postData = $request->getParsedBody();
        // O 'name' do input no HTML é 'titulo'
        $url = filter_var($postData['url'] ?? '', FILTER_SANITIZE_URL);
        $title = filter_var($postData['titulo'] ?? '', FILTER_SANITIZE_STRING); 

        if ($url === false || $title === false || empty($title) || empty($url)) {
            return new Response(302, ['Location' => '/novo-video?sucesso=0']);
        }

        // 3. Criar a entidade (Corrigindo a linha 35)
        $video = new Video(url: $url, title: $title);
        
        // 4. LÓGICA DE UPLOAD DE IMAGEM (A parte que faltava)
        $files = $request->getUploadedFiles();
        /** @var ?UploadedFileInterface $uploadedImage */
        $uploadedImage = $files['image'] ?? null;

        if ($uploadedImage !== null && $uploadedImage->getError() === UPLOAD_ERR_OK) {
            // Gera nome seguro
            $safeFileName = uniqid('upload_') . '_' . pathinfo(
                $uploadedImage->getClientFilename(), 
                PATHINFO_BASENAME
            );
            
            // Move o arquivo
            $targetPath = __DIR__ . '/../../public/img/uploads/' . $safeFileName;
            $uploadedImage->moveTo($targetPath);
            
            // Salva o nome do arquivo no objeto
            // A "planta" Video (Modelo/Video.php) permite isso
            $video->file_path = $safeFileName;
        }

        // 5. Salvar no banco
        $success = $this->videoRepository->add($video);

        if ($success === false) {
            return new Response(302, ['Location' => '/novo-video?sucesso=0']);
        } else {
            // Sucesso! Redireciona para a nova lista de vídeos
            return new Response(302, ['Location' => '/videos?sucesso=1']);
        }
    }
}