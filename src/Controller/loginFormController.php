<?php
declare(strict_types=1);
namespace Alura\Mvc\Controller;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginFormController implements RequestHandlerInterface
{
    // Construtor VAZIO (concorda com a Receita)
    public function __construct()
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (array_key_exists('logado', $_SESSION) && $_SESSION['logado'] === true) {
            return new Response(302, ['Location' => '/videos']); // Manda para a lista
        }
        ob_start();
        require_once __DIR__ . '/../../views/login-form.php';
        $htmlBody = ob_get_clean();
        return new Response(200, [], $htmlBody);
    }
}