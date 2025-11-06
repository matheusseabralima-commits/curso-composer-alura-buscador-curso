<?php
// 1. PUXA O "PÃO DE CIMA" (Head, Menu, CSS e as MENSAGENS DE SESSÃO)
require_once __DIR__ . '/inicio-html.php';

/** * @var \Alura\Mvc\Modelo\Video[] $videos 
 * Esta variável $videos vem do 'VideoListController'
 */
?>

<ul class="videos__container" alt="videos alura">
    
    <?php if (empty($videos)): ?>
        <li class="videos__item">
            <p>Nenhum vídeo cadastrado no momento.</p>
        </li>
    <?php endif; ?>

    <?php foreach ($videos as $video): ?>
        <li class="videos__item">
            
            <a href="<?= htmlspecialchars($video->url); ?>" target="_blank" title="Assistir: <?= htmlspecialchars($video->title); ?>">
                
                <?php if ($video->file_path !== null && file_exists(__DIR__ . '/../../img/uploads/' . $video->file_path)): ?>
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
                <h3><?= htmlspecialchars($video->title); ?></h3>
                <div class="acoes-video">
                    <a href="/editar-video?id=<?= $video->id; ?>">Editar</a>
                    <a href="/remover-video?id=<?= $video->id; ?>">Excluir</a>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

<?php
// 3. PUXA O "PÃO DE BAIXO" (Fechamento do HTML)
require_once __DIR__ . '/fim-html.php';
?>