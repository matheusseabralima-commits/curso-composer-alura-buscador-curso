<?php

require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Receber dados do formulário (URL e Título)
    $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);

    // 2. Receber dados do arquivo (Imagem)
    // Arquivos são tratados com a superglobal $_FILES
    $image = $_FILES['image'];
    $imagePath = null; // Inicia como nulo

    // 3. Validação básica (URL, Título e se a imagem foi enviada)
    if ($url === false || empty($titulo) || $image['error'] !== UPLOAD_ERR_OK) {
        header("Location: enviar-video.php?erro=1");
        exit();
    }

    // 4. Processar o arquivo de imagem
    
    // Define o diretório de destino (CRIE ESTA PASTA "uploads" DENTRO DA SUA PASTA "img")
    $uploadDir = 'img/uploads/';
    
    // Pega a extensão do arquivo (ex: .jpg, .png)
    $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
    
    // Cria um nome de arquivo único para evitar conflitos
    $fileName = uniqid('video_capa_') . '.' . $extension;
    
    // Define o caminho completo de destino
    $destination = $uploadDir . $fileName;
    
    // Tenta mover o arquivo do local temporário para o destino
    if (move_uploaded_file($image['tmp_name'], $destination)) {
        // Se o upload deu certo, definimos o $imagePath para salvar no banco
        $imagePath = $destination;
    } else {
        // Se o upload falhar, redireciona com erro
        header("Location: enviar-video.php?erro=upload");
        exit();
    }

    // 5. Inserir no Banco de Dados (AGORA INCLUINDO o image_path)
    
    // ATENÇÃO: A query SQL mudou para incluir a nova coluna (image_path)
    $sql = "INSERT INTO videos (url, title, image_path) VALUES (:url, :title, :image_path)";
    $statement = $pdo->prepare($sql);

    $statement->bindValue(':url', $url);
    $statement->bindValue(':title', $titulo);
    $statement->bindValue(':image_path', $imagePath); // Salva o caminho do arquivo

    if ($statement->execute()) {
        header("Location: index.php?sucesso=1");
        exit();
    } else {
        // Se a query falhar
        header("Location: enviar-video.php?erro=db");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF8">
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
        
        <form class="container__formulario" 
              action="novo-video.php" 
              method="post" 
              enctype="multipart/form-data">
            
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
            
            <div class="formulario__campo">
                <label class="campo__etiqueta" for="image">Imagem do vídeo</label>
                <input name="image"
                       accept="image/*"
                       type="file"
                       class="campo__escrita"
                       required
                       id='image' />
            </div>
            
            <button type="submit" class="formulario__botao">Enviar</button>
            
        </form>
    </main>
</body>

</html>