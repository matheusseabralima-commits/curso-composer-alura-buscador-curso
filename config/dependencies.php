<?php
declare(strict_types=1);

// 1. IMPORTAÇÕES PRINCIPAIS (Conserta o "Class not found")
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use PDO;

// 2. IMPORTAÇÃO DO REPOSITÓRIO
use Alura\Mvc\Repository\VideoRepository;

// 3. IMPORTAÇÃO DE TODOS OS CONTROLLERS
use Alura\Mvc\Controller\{
    VideoFormController,
    JsonVideoListController,
    EditVideoController,
    Error404Controller,
    VideoCreateController,
    VideoListController,
    VideoRemoveController,
    LogoutController,
    LoginFormController,
    LoginController
};

$builder = new ContainerBuilder();

$builder->addDefinitions([
    
    // --- Receitas de Infraestrutura ---
    PDO::class => function(): PDO {
        $dbPath = __DIR__ . '/../banco.sqlite';
        return new PDO("sqlite:$dbPath");
    },
    VideoRepository::class => function(ContainerInterface $c) {
        return new VideoRepository($c->get(PDO::class));
    },

    // --- Receitas dos Controllers de Vídeo ---
    VideoListController::class => function(ContainerInterface $c) {
        return new VideoListController($c->get(VideoRepository::class));
    },
    VideoFormController::class => function(ContainerInterface $c) {
        return new VideoFormController($c->get(VideoRepository::class));
    },
    VideoCreateController::class => function(ContainerInterface $c) {
        return new VideoCreateController($c->get(VideoRepository::class));
    },
    EditVideoController::class => function(ContainerInterface $c) {
        return new EditVideoController($c->get(VideoRepository::class));
    },
    VideoRemoveController::class => function(ContainerInterface $c) {
        return new VideoRemoveController($c->get(VideoRepository::class));
    },
    JsonVideoListController::class => function(ContainerInterface $c) {
        return new JsonVideoListController($c->get(VideoRepository::class));
    },

    // --- Receitas de Login/Sistema (Conserta o erro da linha 61) ---
    LoginFormController::class => function(): LoginFormController {
        return new LoginFormController(); // Não precisa de dependências
    },
    LoginController::class => function(ContainerInterface $c): LoginController {
        return new LoginController($c->get(PDO::class)); // Precisa do PDO
    },
    LogoutController::class => function(): LogoutController {
        return new LogoutController();
    },
    Error404Controller::class => function() {
        return new Error404Controller();
    }
]);

return $builder->build();