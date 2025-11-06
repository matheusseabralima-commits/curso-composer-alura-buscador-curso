<?php
declare(strict_types=1);
namespace Alura\Mvc\Controller;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PDO;

class LoginController implements RequestHandlerInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $postData = $request->getParsedBody();
        $email = filter_var($postData['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $postData['password'] ?? '';

        if ($email === false || $email === null) {
            // CORREÇÃO: Usa a sessão para o erro
            $_SESSION['error_message'] = 'E-mail ou senha inválidos.';
            return new Response(302, ['Location' => '/login']);
        }

        $sql = 'SELECT * FROM users WHERE email = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $email);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário existe E se a senha está correta
        $correctPassword = (is_array($userData) && password_verify($password, $userData['password']));

        if (!$correctPassword) {
            // CORREÇÃO: Usa a sessão para o erro
            // (Usamos a mesma mensagem genérica por segurança)
            $_SESSION['error_message'] = 'E-mail ou senha inválidos.';
            return new Response(302, ['Location' => '/login']);
        }

        // Sucesso!
        session_regenerate_id(true); 
        $_SESSION['logado'] = true;
        return new Response(302, ['Location' => '/videos']);
    }
}