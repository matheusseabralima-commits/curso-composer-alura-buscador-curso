<?php
declare(strict_types=1);

$dbPath = __DIR__ . '/banco.sqlite';
$pdo = new PDO("sqlite:$dbPath");

$email = $argv[1] ?? null;
$password = $argv[2] ?? null;

if ($email === null || $password === null) {
    echo "Erro: Passe um e-mail e uma senha." . PHP_EOL;
    echo "Exemplo: php cria-usuario.php email@teste.com 1234" . PHP_EOL;
    exit(1);
}

// A LINHA MAIS IMPORTANTE:
$hash = password_hash($password, PASSWORD_ARGON2ID);

$sql = 'INSERT INTO users (email, password) VALUES (?, ?);';
$statement = $pdo->prepare($sql);
$statement->bindValue(1, $email);
$statement->bindValue(2, $hash); // Salva o HASH, não a senha

if ($statement->execute()) {
    echo "Usuário '$email' criado com HASH com sucesso!" . PHP_EOL;
} else {
    echo "Erro ao criar usuário." . PHP_EOL;
}