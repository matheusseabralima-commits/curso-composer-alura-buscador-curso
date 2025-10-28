<?php

// 1. Inclui o arquivo de conexão com o banco de dados
require_once 'conexao.php';

// 2. Verifica se o formulário foi enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Receber e validar os dados do formulário (URL e Título)
    $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);

    // 4. Receber os dados do arquivo (Imagem)
    // Arquivos são tratados com a superglobal $_FILES
    $image = $_FILES['image'];
    $imagePath = null; // Inicia o caminho da imagem como nulo

    // 5. Validação dos dados
    // Verifica se a URL é válida, se o título não está vazio e se a imagem foi enviada sem erros
    if ($url === false || empty($titulo) || $image['error'] !== UPLOAD_ERR_OK) {
        // Se houver erro, redireciona de volta ao formulário com uma mensagem
        header("Location: enviar-video.php?erro=validacao");
        exit();
    }

    // 6. Processar o upload da imagem
    
    // ATENÇÃO: Crie esta pasta "uploads" dentro da sua pasta "img" (caminho: /img/uploads/)
    $uploadDir = 'img/uploads/';
    
    // Pega a extensão original do arquivo (ex: .jpg, .png)
    $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
    
    // Gera um nome de arquivo único para evitar que um arquivo substitua outro
    // Ex: video_capa_60b8d29f5f1a2.jpg
    $fileName = uniqid('video_capa_') . '.' . $extension;
    
    // Define o caminho completo onde a imagem será salva
    $destination = $uploadDir . $fileName;
    
    // Tenta mover o arquivo da pasta temporária do PHP para o destino final
    if (move_uploaded_file($image['tmp_name'], $destination)) {
        // Se o upload deu certo, guarda o caminho do destino para salvar no banco
        $imagePath = $destination;
    } else {
        // Se falhar ao mover o arquivo, redireciona com um erro específico
        header("Location: enviar-video.php?erro=upload_falhou");
        exit();
    }

    // 7. Inserir os dados no Banco de Dados
    
    // ATENÇÃO: Sua tabela "videos" precisa ter a coluna "image_path"
    $sql = "INSERT INTO videos (url, title, image_path) VALUES (:url, :title, :image_path)";
    $statement = $pdo->prepare($sql);

    // Associa os valores às variáveis da query
    $statement->bindValue(':url', $url);
    $statement->bindValue(':title', $titulo);
    $statement->bindValue(':image_path', $imagePath); // Salva o caminho da imagem

    // 8. Executar e redirecionar
    if ($statement->execute()) {
        // Se tudo deu certo, redireciona para a página inicial com sucesso
        header("Location: index.php?sucesso=1");
        exit();
    } else {
        // Se a inserção no banco falhar
        header("Location: enviar-video.php?erro=db");
        exit();
    }
} else {
    // Se alguém tentar acessar novo-video.php diretamente pelo navegador
    header("Location: enviar-video.php");
    exit();
}
?>