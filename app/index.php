<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
// require_once './middlewares/Logger.php';

require_once './controllers/TiendaController.php';
require_once './controllers/VentaController.php';
require_once './middlewares/AuthPrendaMW.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();



// Instantiate App
$app = AppFactory::create();


// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();



// Routes Tienda
$app->group('/tienda', function (RouteCollectorProxy $group) {
  $group->get('[/]', \TiendaController::class . ':TraerTodos');
  $group->get('/{idprenda}', \TiendaController::class . ':TraerUno');
  $group->post('[/alta]', \TiendaController::class . ':CargarUno')->add(\AuthPrendaMW::class . ':ValidarParamsPrenda');
  $group->post('/consultar', \TiendaController::class . ':ConsultarTipoPrenda');
  // $group->put('/modificarestado/{id}', \TiendaController::class . ':ModificarUno');
  //$group->delete('/eliminarpedido/{id}', \TiendaController::class . ':BorrarUno');
});



// Routes Venta
$app->group('/ventas', function (RouteCollectorProxy $group) {
  $group->post('[/alta]', \VentaController::class . ':CargarUno');

  $group->get('[/]', \VentaController::class . ':TraerTodos');
  $group->get('/{descripcionProducto}', \VentaController::class . ':TraerUno');
  $group->put('/modificarestado/{id}', \VentaController::class . ':ModificarUno');
  $group->delete('/eliminarproducto/{id}', \VentaController::class . ':BorrarUno');
});




$app->get('[/]', function (Request $request, Response $response) {
  $payload = json_encode(array("mensaje" => "Slim Framework 4 PHP"));

  $response->getBody()->write($payload);
  return $response->withHeader('Content-Type', 'application/json');
});


$app->run();
