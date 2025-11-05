<?php
declare(strict_types=1);

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

require_once __DIR__ . '/vendor/autoload.php';

/** @var ContainerInterface $diContainer */
$diContainer = require_once __DIR__ . '/config/dependencies.php';

// O "MAPA" NOVO (com GET/POST)
$rotas = [
    'GET|/' => Alura\Mvc\Controller\LoginFormController::class,
    'GET|/login' => Alura\Mvc\Controller\LoginFormController::class,
    'POST|/login' => Alura\Mvc\Controller\LoginController::class,
    'GET|/logout' => Alura\Mvc\Controller\LogoutController::class,
    'GET|/videos' => Alura\Mvc\Controller\VideoListController::class,
    'GET|/novo-video' => Alura\Mvc\Controller\VideoFormController::class,
    'POST|/novo-video' => Alura\Mvc\Controller\VideoCreateController::class,
    'GET|/editar-video' => Alura\Mvc\Controller\VideoFormController::class,
    'POST|/editar-video' => Alura\Mvc\Controller\EditVideoController::class,
    'GET|/remover-video' => Alura\Mvc\Controller\VideoRemoveController::class,
    'GET|/json-videos' => Alura\Mvc\Controller\JsonVideoListController::class,
];

session_start();
session_regenerate_id();

$pathInfo = $_SERVER['PATH_INFO'] ?? '/';
$httpMethod = $_SERVER['REQUEST_METHOD'];
$key = "$httpMethod|$pathInfo";

$isLoginRoute = $key === 'GET|/' || $key === 'GET|/login' || $key === 'POST|/login';
if (array_key_exists('logado', $_SESSION) === false && $isLoginRoute === false) {
    header('Location: /login');
    return;
}

if (array_key_exists($key, $rotas)) {
    $controllerClass = $rotas[$key];
} else {
    $controllerClass = Alura\Mvc\Controller\Error404Controller::class;
}

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
    $psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory
);
$request = $creator->fromGlobals();

/** @var RequestHandlerInterface $controller */
$controller = $diContainer->get($controllerClass);
$response = $controller->handle($request);

http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
echo $response->getBody();