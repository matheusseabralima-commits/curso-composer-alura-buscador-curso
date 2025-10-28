<?php
// INICIA A SESSÃO
session_start();

// ESTE É O PROTETOR. Se não está logado, expulsa para o login.php
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: /login.php'); 
    exit();
}

// Se chegou aqui, está logado.
require_once 'conexao.php';
$videoList = $pdo->query('SELECT * FROM videos;')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/estilos.css">
    <link rel="stylesheet" href="./css/flexbox.css">
    <title>AluraPlay</title>
</head>
<body>
    <header>
        <nav class="cabecalho">
            <a class="logo" href="./index.php"></a>
            <div class="cabecalho__icones">
                <a href="enviar-video.php" class="cabecalho__videos"></a>
                <!-- Link para o logout -->
                <a href="fazer-logout.php" class="cabecalho__sair">Sair</a>
            </div>
        </nav>
    </header>

    <ul class="videos__container" alt="videos alura">
        <!-- A lista de vídeos -->
        <?php foreach ($videoList as $video): ?>
            <li class="videos__item">
                
                <!-- 
                    INÍCIO DA ALTERAÇÃO:
                    Trocamos o <iframe> pela imagem de capa clicável.
                    Note que usamos $video['image_path'] que vem do banco.
                -->
                <a href="<?= htmlspecialchars($video['url']); ?>" target="_blank" title="Assistir: <?= htmlspecialchars($video['title']); ?>">
                    <img src="<?= htmlspecialchars($video['image_path']); ?>" 
                         alt="Capa do vídeo <?= htmlspecialchars($video['title']); ?>" 
                         style="width: 100%; height: 72%; object-fit: cover;">
                </a>
                <!-- FIM DA ALTERAÇÃO -->

                <div class="descricao-video">
                    <img src="./img/logo.png" alt="logo canal alura">
                    <h3><?= htmlspecialchars($video['title']); ?></h3>
                    <div class="acoes-video">
                        <a href="editar-video.php?id=<?= $video['id']; ?>">Editar</a>
                        <a href="remover-video.php?id=<?= $video['id']; ?>">Excluir</a>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
