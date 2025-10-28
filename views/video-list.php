<?php
require_once __DIR__ . '/inicio-html.php';
/** @var \Alura\Mvc\Entity\Video[] $videoList */
?>

<ul class="videos__container">
    <?php foreach ($videoList as $video): ?>
        <li class="videos__item">
            
            <!-- 
                INÍCIO DA ALTERAÇÃO:
                Removemos o <iframe> e colocamos a imagem de capa
                dentro de um link para a URL do vídeo.
            -->
            <a href="<?= $video->url; ?>" target="_blank" title="Assistir: <?= $video->title; ?>">
                <img src="<?= $video->image_path; ?>" 
                     alt="Capa do vídeo <?= $video->title; ?>" 
                     style="width: 100%; height: 72%; object-fit: cover;">
            </a>
            <!-- FIM DA ALTERAÇÃO -->

            <div class="descricao-video">
                <h3><?= $video->title; ?></h3>
                <div class="acoes-video">
                    <a href="/editar-video?id=<?= $video->id; ?>">Editar</a>
                    <a href="/remover-video?id=<?= $video->id; ?>">Excluir</a>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

<?php require_once __DIR__ . '/fim-html.php';
