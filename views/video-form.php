<?php
/** @var \Alura\Mvc\Modelo\Video $video */ 
?>
<main class="container">
        
        <form class="container__formulario" 
              method="post" 
              action="<?= $video->id === null ? '/criar-video' : '/editar-video'; ?>"
              enctype="multipart/form-data">
            
            <h2 class="formulario__titulo">Envie um vídeo!</h2>
            
            <div class="formulario__campo">
                <label class="campo__etiqueta" for="image">Capa do vídeo (opcional)</label>
                <input name="image" 
                       accept="image/*"
                       type="file"
                       class="campo__escrita" id='image' />
            </div>

            <input class="formulario__botao" type="submit" value="Enviar" />
        </form>
    </main>