<?php
require_once './models/Venta.php';
require_once './models/Tienda.php';

class VentaController extends Venta
{

  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $descripcion = $parametros['descripcion'];
    $tipo = $parametros['tipo'];
    $talla = $parametros['talla'];
    $stock = $parametros['stock'];
    $email = $parametros['email'];
    $nroPedido = $parametros['nropedido'];

    $listaPrendas = Tienda::obtenerTodos();

    $ventaHecha = true;

    foreach ($listaPrendas as $prenda) {
      if ($descripcion == $prenda->descripcion && $tipo == $prenda->tipo && $talla == $prenda->talla && $prenda->stock >= $stock) {

        // Creo la venta
        $venta = new Venta();
        $venta->descripcion = $descripcion;
        $venta->tipo = $tipo;
        $venta->talla = $talla;
        $venta->stock = $stock;
        $venta->email = $email;
        $venta->nroPedido = $nroPedido;
        $venta->crearVenta();

        $payload = json_encode(array("mensaje" => "Venta realizada con exito"));
        $ventaHecha = true;

        //Descuento el stock solicitado por el ususuario
        Tienda::DescontarStock($stock, $prenda->idPrenda);

        $archivosCargados = $request->getUploadedFiles();
        $fotoPrenda = $archivosCargados['fotoprenda'];

        //Recorto el email hasta el @
        $nombreUsuario = explode('@', $email)[0];

        // Declaro parte del nombre final del archivo
        $fechaYTipo = date("d-m-Y") . ".jpg";

        // Ruta a la que mandaremos el archivo
        $rutaImagen = "../ImagenesVentas2024/" . $descripcion . $tipo . $talla . $nombreUsuario . $fechaYTipo;

        // Guardo el archivo
        $fotoPrenda->moveTo($rutaImagen);

        break;
      } else {
        $ventaHecha = false;
      }
    }

    if (!$ventaHecha) {
      $payload = json_encode(array("mensaje" => "No se realizó la venta"));
    }


    $response->getBody()->write($payload);

    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public function TraerTodos($request, $response, $args)
  {
    $lista = Venta::obtenerTodos();
    $payload = json_encode(array("listaVentas" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $nroPedido = $args['nropedido'];

    $existeVenta = false;

    $tallaNueva = $parametros['tallanueva'];
    $talla = $parametros['talla'];
    $descripcion = $parametros['descripcion'];
    $tipo = $parametros['tipo'];
    $stock = $parametros['stock'];

    $listaVentas = Venta::obtenerTodos();

    foreach ($listaVentas as $venta) {
      if (
        $venta->talla == $talla && $venta->descripcion == $descripcion
        && $venta->tipo == $tipo && $venta->stock == $stock && $nroPedido == $venta->nroPedido
      ) {

        $existeVenta = true;
        break;
      } else {
        $existeVenta = false;
      }
    }

    if ($existeVenta) {
      Venta::modificarPedido($nroPedido, $tallaNueva);
      $payload = json_encode(array("mensaje" => "Talla del pedido modificada con exito"));
    } else {
      $payload = json_encode(array("mensaje" => "No existe el numero de pedido: " . $nroPedido . " que coincida con los datos suministrados"));
    }


    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
