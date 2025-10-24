<?php
require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($url === false || empty($titulo)) {
        header("Location: enviar-video.php?erro=1");
        exit();
    }

    $sql = "INSERT INTO videos (url, title) VALUES (:url, :title)";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':url', $url);
    $statement->bindValue(':title', $titulo);

    if ($statement->execute()) {
        header("Location: index.php?sucesso=1");
    } else {
        header("Location: enviar-video.php?erro=2");
    }
    exit();
}