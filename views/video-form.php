<?php
// 1. PUXA O "PÃO DE CIMA" (Head, Menu, CSS)
require_once __DIR__ . '/inicio-html.php';

/** @var \Alura\Mvc\Modelo\Video|null $video */
?>

<main class="container">
    <form class="container__formulario"
          method="post"
          enctype="multipart/form-data"
          
          action="<?= isset($video) ? "/editar-video?id={$video->id}" : '/novo-video'; ?>">

        <h2 class="formulario__titulo">
            <?= isset($video) ? 'Editando Vídeo' : 'Envie um vídeo!'; ?>
        </h2>

        <div class="formulario__campo">
            <label class="campo__etiqueta" for="url">Link embed</label>
            <input name="url"
                   value="<?= $video?->url; ?>"
                   class="campo__escrita"
                   required
                   placeholder="Por exemplo: https://www.youtube.com/embed/FAY1K2aUg5g"
                   id='url'/>
        </div>

        <div class="formulario__campo">
            <label class="campo__etiqueta" for="titulo">Titulo do vídeo</label>
            <input name="titulo"
                   value="<?= $video?->title; ?>"
                   class="campo__escrita"
                   required
                   placeholder="Neste campo, dê o nome do vídeo"
                   id='titulo'/>
        </div>

        <div class="formulario__campo">
            <label class="campo__etiqueta" for="image">Imagem do vídeo</label>
            <input name="image"
                   accept="image/*"
                   type="file"
                   class="campo__escrita"
                   id='image'/>
        </div>

        <?php if (isset($video)): ?>
            <input type="hidden" name="id" value="<?= $video->id; ?>">
        <?php endif; ?>

        <input class="formulario__botao" type="submit" value="Enviar"/>
    </form>
</main>

<?php
// 3. PUXA O "PÃO DE BAIXO" (Fechamento do HTML)
require_once __DIR__ . '/fim-html.php';
?>