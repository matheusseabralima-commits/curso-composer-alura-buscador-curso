<?php
require_once 'conexao.php';

// Validação para garantir que um ID foi passado pela URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Se o ID não for um número válido, redireciona
if ($id === false) {
    header("Location: index.php");
    exit();
}

// Lógica para processar a ATUALIZAÇÃO quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
    $idUpdate = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($url === false || empty($titulo) || $idUpdate === false) {
        header("Location: editar-video.php?id=$id&erro=1");
        exit();
    }

    $sql = "UPDATE videos SET url = :url, title = :title WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':url', $url);
    $statement->bindValue(':title', $titulo);
    $statement->bindValue(':id', $idUpdate, PDO::PARAM_INT);

    if ($statement->execute()) {
        header("Location: index.php?sucesso=2");
    } else {
        header("Location: editar-video.php?id=$id&erro=2");
    }
    exit();
}

// Lógica para buscar os dados e PREENCHER O FORMULÁRIO
$statement = $pdo->prepare('SELECT * FROM videos WHERE id = ?;');
$statement->bindValue(1, $id, PDO::PARAM_INT);
$statement->execute();
$video = $statement->fetch(PDO::FETCH_ASSOC);

// Se não encontrou o vídeo, redireciona para a página inicial
if ($video === false) {
    header("Location: index.php");
    exit();
}
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
    <link rel="stylesheet" href="./css/estilos-form.css">
    <link rel="stylesheet" href="./css/flexbox.css">
    
    <title>AluraPlay - Editar Vídeo</title>
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
</head>
<body>
    <header>
        <nav class="cabecalho">
            <a class="logo" href="./index.php"></a>
            <div class="cabecalho__icones">
                <a href="enviar-video.php" class="cabecalho__videos"></a>
                <a href="#" class="cabecalho__sair">Sair</a>
            </div>
        </nav>
    </header>
    <main class="container">
        <form class="container__formulario" method="post">
            <h2 class="formulario__titulo">Edite o vídeo!</h2>

            <?php if (isset($_GET['erro']) && $_GET['erro'] == '1'): ?>
                <div class="mensagem-erro">
                    <p>Erro: Por favor, preencha todos os campos corretamente.</p>
                </div>
            <?php endif; ?>

            <div class="formulario__campo">
                <label class="campo__etiqueta" for="url">Link embed</label>
                <input name="url" class="campo__escrita" required value="<?= htmlspecialchars($video['url']); ?>"
                    placeholder="Por exemplo: https://www.youtube.com/embed/FAY1K2aUg5g" id='url' />
            </div>
            <div class="formulario__campo">
                <label class="campo__etiqueta" for="titulo">Titulo do vídeo</label>
                <input name="titulo" class="campo__escrita" required value="<?= htmlspecialchars($video['title']); ?>"
                    placeholder="Neste campo, dê o nome do vídeo" id='titulo' />
            </div>
            <input type="hidden" name="id" value="<?= $video['id']; ?>">
            <input class="formulario__botao" type="submit" value="Atualizar" />
        </form>
    </main>
</body>
</html>