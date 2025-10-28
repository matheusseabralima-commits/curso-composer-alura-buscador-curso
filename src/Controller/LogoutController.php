<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

class LogoutController implements Controller
{
    public function processaRequisicao(): void
    {
        // Destrói a sessão (faz o logout)
        session_destroy();
        
        // Redireciona o usuário para a página de login
        header('Location: /login');
    }
}
