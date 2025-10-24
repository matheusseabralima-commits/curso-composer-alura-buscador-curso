<?php

declare(strict_types=1);

return [
    'GET|/' => [\Alura\Mvc\Controller\VideoListController::class, 'processaRequisicao'],

    'GET|/novo-video' => [\Alura\Mvc\Controller\VideoFormsController::class, 'showForm'],
    'POST|/novo-video' => [\Alura\Mvc\Controller\VideoCreateController::class, 'create'],

    'GET|/editar-video' => [\Alura\Mvc\Controller\VideoFormsController::class, 'showForm'],
    'POST|/editar-video' => [\Alura\Mvc\Controller\VideoEditController::class, 'Edit'],

    'GET|/remover-video' => [\Alura\Mvc\Controller\VideoRemoveController::class, 'deleteVideo'],

];