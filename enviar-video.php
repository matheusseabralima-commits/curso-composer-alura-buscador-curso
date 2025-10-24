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
        exit();
    }
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
    <title>AluraPlay - Enviar Vídeo</title>
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
</head>

<body>

    <header>
        <nav class="cabecalho">
            <a class="logo" href="./index.php"></a>

            <div class="cabecalho__icones">
                <a href="./enviar-video.php" class="cabecalho__videos"></a>
                <a href="./login.php" class="cabecalho__sair">Sair</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <form class="container__formulario" action="novo-video.php" method="post">
            <h2 class="formulario__titulo">Envie um vídeo!</h2>
                <div class="formulario__campo">
                    <label class="campo__etiqueta" for="url">Link embed</label>
                    <input name="url" class="campo__escrita" required
                        placeholder="Por exemplo: https://www.youtube.com/embed/FAY1K2aUg5g" id='url' />
                </div>
                <div class="formulario__campo">
                    <label class="campo__etiqueta" for="titulo">Título</label>
                    <input name="titulo" class="campo__escrita" required
                        placeholder="Por exemplo: Conhecendo a linguagem Go | Hipsters.Talks" id='titulo' />
                </div>
                <button class="formulario__botao">Enviar</button>
        </form>
    </main>
</body>

</html>