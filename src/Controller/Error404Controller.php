<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface; // <-- Interface CORRETA

// Implementa a interface CORRETA
class Error404Controller implements RequestHandlerInterface
{
    // Usa o método CORRETO
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = '<h1>Erro 404 - Página não encontrada</h1>';
        return new Response(404, [], $body);
    }
}