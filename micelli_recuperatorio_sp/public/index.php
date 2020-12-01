<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Config\Database;

//Controladores de clases
use App\Controllers\UserController;
use App\Controllers\MascotaController;
use App\Controllers\TurnoController;
//Middlewares
use App\Middlewares\JsonMiddleware;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\ValidarAdminMiddleware;
use App\Middlewares\ValidarClienteMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
//Recibe /nombre carpeta proyecto/nombre carpeta que tiene el index
$app->setBasePath('/micelli_recuperatorio_sp/public'); //cambiar

//Instancio un objeto Config/database llamando a su constructor.
//En este caso nos conectamos a la base de datos : baseparcial
new Database;

//Ruta: localhost/micelli_recuperatorio_sp/public/

//Punto1
$app->post('/users[/]', UserController::class . ':registro')->add(new JsonMiddleware);

//Punto2
$app->post('/login[/]', UserController::class . ':login')->add(new JsonMiddleware);

//Punto3
$app->post('/mascota[/]', MascotaController::class . ':registro')->add(new ValidarAdminMiddleware)->add(new JsonMiddleware);

//Punto4
$app->post('/turno[/]', TurnoController::class . ':registro')->add(new ValidarClienteMiddleware)->add(new JsonMiddleware);

//Punto5
$app->get('/turnos[/]', TurnoController::class . ':funcionJoin')->add(new ValidarAdminMiddleware)->add(new JsonMiddleware);

//Punto6
$app->put('/turno/{idTurno}', TurnoController::class . ':asignarAtendido')->add(new ValidarAdminMiddleware)->add(new JsonMiddleware);

$app->run();