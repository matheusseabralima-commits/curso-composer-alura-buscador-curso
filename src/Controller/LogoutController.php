<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LogoutController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // 1. Destruir a sessão
        session_destroy();
        
        // 2. Limpar a variável $_SESSION (garantia)
        $_SESSION = [];

        // 3. Redirecionar para a página de login
        // (O seu index.php tem uma rota '/login')
        return new Response(302, ['Location' => '/login']);
    }
}