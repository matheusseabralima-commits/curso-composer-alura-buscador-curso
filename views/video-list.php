<?php
/** * @var \Alura\Mvc\Modelo\Video[] $videos 
 * Esta variável $videos vem do 'VideoListController'
 */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="/css/reset.css">
    <link rel="stylesheet" href="/css/estilos.css">
    <link rel="stylesheet" href="/css/flexbox.css">
    <title>AluraPlay</title>
</head>
<body>
    <header>
        <nav class="cabecalho">
            <a class="logo" href="/"></a>
            <div class="cabecalho__icones">
                <a href="/novo-video" class="cabecalho__videos"></a>
                <a href="/logout" class="cabecalho__sair">Sair</a>
            </div>
        </nav>
    </header>

    <ul class="videos__container" alt="videos alura">
        
        <?php foreach ($videos as $video): ?>
            <li class="videos__item">
                
                <a href="<?= htmlspecialchars($video->url); ?>" target="_blank" title="Assistir: <?= htmlspecialchars($video->title); ?>">
                    
                    <?php if ($video->file_path !== null): ?>
                        <img src="/img/uploads/<?= htmlspecialchars($video->file_path); ?>" 
                             alt="Capa do vídeo <?= htmlspecialchars($video->title); ?>" 
                             style="width: 100%; height: 72%; object-fit: cover;">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/260x146?text=Sem+Capa" 
                             alt="Vídeo sem capa"
                             style="width: 100%; height: 72%; object-fit: cover;">
                    <?php endif; ?>
                </a>

                <div class="descricao-video">
                    <img src="/img/logo.png" alt="logo canal alura">
                    <h3><?= htmlspecialchars($video->title); ?></h3>
                    <div class="acoes-video">
                        <a href="/editar-video?id=<?= $video->id; ?>">Editar</a>
                        <a href="/remover-video?id=<?= $video->id; ?>">Excluir</a>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>