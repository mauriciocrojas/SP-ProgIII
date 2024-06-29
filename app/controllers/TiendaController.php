<?php
require_once './models/Tienda.php';

class TiendaController extends Tienda
{

  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $descripcion = $parametros['descripcion'];
    $tipo = $parametros['tipo'];
    $color = $parametros['color'];
    $talla = $parametros['talla'];
    $precio = $parametros['precio'];
    $stock = $parametros['stock'];

    $existePrenda = false;

    $listaPrendas = Tienda::obtenerTodos();

    foreach ($listaPrendas as $prenda) {
      if ($descripcion == $prenda->descripcion && $tipo == $prenda->tipo) {
        Tienda::ActualizarPrendaExistente($parametros['stock'], $parametros['precio'], $prenda->idPrenda);
        $payload = json_encode(array("mensaje" => "La prenda ya existía, por lo que se actualizó su valor y stock"));
        $existePrenda = true;
        break;
      }
    }

    if (!$existePrenda) {
      // Creamos la prenda
      $prenda = new Tienda();
      $prenda->descripcion = $descripcion;
      $prenda->tipo = $tipo;
      $prenda->color = $color;
      $prenda->talla = $talla;
      $prenda->precio = $precio;
      $prenda->stock = $stock;
      $prenda->crearPrenda();

      $archivosCargados = $request->getUploadedFiles();
      $fotoPrenda = $archivosCargados['fotoprenda'];

      // Declaramos parte del nombre del archivo
      $fechaYTipo = date("d-m-Y") . ".jpg";

      // Ruta a la que mandaremos el archivo
      $rutaImagen = "../ImagenesRopa2024/" . $parametros['descripcion'] . $parametros['tipo']  . $fechaYTipo;

      // Guardo el archivo
      $fotoPrenda->moveTo($rutaImagen);

      $payload = json_encode(array("mensaje" => "Prenda creada con exito"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ConsultarTipoPrenda($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $descripcion = $parametros['descripcion'];
    $tipo = $parametros['tipo'];
    $color = $parametros['color'];

    $listaPrendas = Tienda::obtenerTodos();

    $existePrendaExacta = false;
    $existePrendaTipo = true;
    $existePrendaDescripcion = true;

    foreach ($listaPrendas as $prenda) {
      if ($descripcion == $prenda->descripcion && $tipo == $prenda->tipo && $color == $prenda->color) {
        $existePrendaExacta = true;
        break;
      }
      if ($descripcion != $prenda->descripcion && $tipo == $prenda->tipo && $color == $prenda->color) {
        $existePrendaDescripcion = false;
      }
      if ($descripcion == $prenda->descripcion && $tipo != $prenda->tipo && $color == $prenda->color) {
        $existePrendaTipo = false;
      }
    }

    if ($existePrendaExacta) {
      $payload = json_encode(array("mensaje" => "Existen prendas con esa descripcion, tipo y color"));
    } else {
      if (!$existePrendaDescripcion) {
        $payload = json_encode(array("mensaje" => "No hay prendas con descripcion: " . $descripcion));
      } elseif (!$existePrendaTipo) {
        $payload = json_encode(array("mensaje" => "No hay prendas de tipo: " . $tipo));
      } else {
        $payload = json_encode(array("mensaje" => "No hay prendas que coincidan con los criterios dados"));
      }
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public function TraerTodos($request, $response, $args)
  {
    $lista = Tienda::obtenerTodos();
    $payload = json_encode(array("listaPrendas" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    // Buscamos pedido por su id
    $idprenda = $args['idprenda'];
    $pedido = Tienda::obtenerUno($idprenda);
    $payload = json_encode($pedido);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $id = $args['id'];
    $estado = $parametros['estado'];
    Tienda::modificarPedido($id, $estado);

    $payload = json_encode(array("mensaje" => "Estado del pedido modificado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $id = $args['id'];
    Tienda::borrarPedido($id);

    $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
