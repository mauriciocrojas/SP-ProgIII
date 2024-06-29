<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AuthPrendaMW extends Tienda
{



  public function ValidarParamsPrenda(Request $request, RequestHandler $handler)
  {
    $params = $request->getParsedBody();
    $archivosCargados = $request->getUploadedFiles();
    $response = new Response();

    if (isset($params['descripcion'], $params['tipo'], $params['color'], $params['talla'], $params['precio'], $params['stock'],  $archivosCargados['fotoprenda'])) {
      $response = $handler->handle($request);
    } else {
      $response->getBody()->write(json_encode(array("error" => "MW: Los parametros no fueron seteados")));
    }

    return $response;
  }
}
