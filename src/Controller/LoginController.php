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
    // O __construct NOVO que aceita o PDO
    public function __construct(private PDO $pdo)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $postData = $request->getParsedBody();
        $email = filter_var($postData['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $postData['password'] ?? '';

        if ($email === false || $email === null) {
            return new Response(302, ['Location' => '/login?sucesso=0']);
        }

        $sql = 'SELECT * FROM users WHERE email = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $email);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        $correctPassword = (is_array($userData) && password_verify($password, $userData['password']));

        if (!$correctPassword) {
            return new Response(302, ['Location' => '/login?sucesso=0']);
        }

        session_regenerate_id(true); 
        $_SESSION['logado'] = true;
        return new Response(302, ['Location' => '/videos']);
    }
}