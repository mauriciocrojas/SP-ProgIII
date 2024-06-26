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

require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './middlewares/AuthPedidoMW.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
//$dotenv->safeLoad();
$dotenv->load();



// Instantiate App
$app = AppFactory::create();

// Set base path
//$app->setBasePath('/');//cuando uso el xampp

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes Usuario
$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->get('/{nombre}', \UsuarioController::class . ':TraerUno');
  $group->post('[/]', \UsuarioController::class . ':CargarUno');
  $group->put('/modificarestado/{id}', \UsuarioController::class . ':ModificarUno');
  $group->delete('/eliminarusuario/{id}', \UsuarioController::class . ':BorrarUno');
});

// Routes Producto
$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->get('/{descripcionProducto}', \ProductoController::class . ':TraerUno');
  $group->post('[/]', \ProductoController::class . ':CargarUno');
  $group->put('/modificarestado/{id}', \ProductoController::class . ':ModificarUno');
  $group->delete('/eliminarproducto/{id}', \ProductoController::class . ':BorrarUno');
});

// Routes Mesa
$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->get('/{id}', \MesaController::class . ':TraerUno');
  $group->post('[/]', \MesaController::class . ':CargarUno');
  $group->put('/modificarestado/{id}', \MesaController::class . ':ModificarUno');
  $group->delete('/eliminarmesa/{id}', \MesaController::class . ':BorrarUno');
});


// $validacionParams = function (Request $request, RequestHandler $handler) {

//   $params = $request->getParsedBody();


//   if (isset($params["idmesa"], $params["nombrecliente"])) {
//     $response = $handler->handle($request);
//   } else {
//     $response = new Response();
//     $response->getBody()->write(json_encode(array("error" => "MW: Los params del pedido no fueron seteados correctamente")));
//   }
//   return $response;
// };

// $app->post('/pedidos', \PedidoController::class . ':CargarUno')
// ->add($validacionParams);

// Routes Pedido
$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos');
  $group->get('/{idpedido}', \PedidoController::class . ':TraerUno');
  $group->post('[/]', \PedidoController::class . ':CargarUno')->add(\AuthPedidoMW::class . ':ValidarParamsPedido');
  $group->post('/cargarimagenmesa/{idpedido}', \PedidoController::class . ':CargarImagenMesa');//Validar
  $group->put('/modificarestado/{id}', \PedidoController::class . ':ModificarUno');
  $group->delete('/eliminarpedido/{id}', \PedidoController::class . ':BorrarUno');
});





$app->get('[/]', function (Request $request, Response $response) {
  $payload = json_encode(array("mensaje" => "Slim Framework 4 PHP"));

  $response->getBody()->write($payload);
  return $response->withHeader('Content-Type', 'application/json');
});


$app->run();
