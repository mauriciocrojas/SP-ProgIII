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
        $venta->idPrenda = $prenda->idPrenda;

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

  //Ventas consultar
  public function TraerPorFecha($request, $response, $args)
  {
    $parametros = $request->getQueryParams();

    if (isset($parametros['fechaVenta'])) {
      $lista = Venta::obtenerPorFecha($parametros['fechaVenta']);
      $payload = json_encode(array("listaVentas" => $lista));
    } else {
      $lista = Venta::obtenerPorFecha();
      $payload = json_encode(array("listaVentas" => $lista));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerPorUsuario($request, $response, $args)
  {
    $parametros = $request->getQueryParams();

    if (isset($parametros['email'])) {
      $lista = Venta::obtenerPorUsuario($parametros['email']);
      $payload = json_encode(array("listaVentas" => $lista));
    } else {
      $payload = json_encode(array("mensaje" => "El usuario no fue seteado"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerPorTipo($request, $response, $args)
  {
    $parametros = $request->getQueryParams();

    if (isset($parametros['tipo'])) {
      $lista = Venta::obtenerPorTipo($parametros['tipo']);
      $payload = json_encode(array("listaVentas" => $lista));
    } else {
      $payload = json_encode(array("mensaje" => "El tipo de producto no fue seteado"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerPorRangoDePrecios($request, $response, $args)
  {
    $parametros = $request->getQueryParams();

    if (isset($parametros['valorA'], $parametros['valorB'])) {
      $lista = Venta::obtenerProductosPorPrecio($parametros['valorA'], $parametros['valorB']);
      $payload = json_encode(array("listaProductos" => $lista));
    } else {
      $payload = json_encode(array("mensaje" => "El rango de precios no fue seteado correctamente"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerIngresosPorDia($request, $response, $args)
  {
    $parametros = $request->getQueryParams();

    if (isset($parametros['fechaVenta'])) {
      $totalVentas = Venta::obtenerIngresosPorDia(true, $parametros['fechaVenta']);
      $mensaje = "Los ingresos por ventas del día " . $parametros['fechaVenta'] . " fueron de: $" . $totalVentas[0]['total_ventas'];
      $payload = json_encode(array("mensaje" => $mensaje));
    } else {
      $ventasPorDia = Venta::obtenerIngresosPorDia(false);
      $mensaje = "Los ingresos por ventas de cada día fueron de: / ";

      foreach ($ventasPorDia as $venta) {
        $mensaje  .=  $venta['fecha_venta'] . ":$" .  $venta['total_ventas'] . " / ";
      }

      $payload = json_encode(array("mensaje" => $mensaje));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerPrendaMasVendida($request, $response, $args)
  {
    $prendaMasVendida = Venta::obtenerProductoMasVendido();
    $payload = json_encode(array("Prenda mas vendida" => $prendaMasVendida));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }


  //
  public function descargarCSV($request, $response, $args)
  {
    $listaVentas = Venta::obtenerTodos();

    $nombreCSV = 'ventas_' . date('Y-m-d') . '.csv';

    $file = fopen('php://memory', 'w');

    $headers = ['idVenta', 'idPrenda', 'nroPedido', 'email', 'descripcion', 'tipo', 'talla', 'stock', 'fechaVenta'];
    fputcsv($file, $headers);

    foreach ($listaVentas as $venta) {
      fputcsv($file, [
        $venta->idVenta,
        $venta->idPrenda,
        $venta->nroPedido,
        $venta->email,
        $venta->descripcion,
        $venta->tipo,
        $venta->talla,
        $venta->stock,
        $venta->fechaVenta
      ]);
    }

    fseek($file, 0);

    $response = $response->withHeader('Content-Type', 'application/csv')
      ->withHeader('Content-Disposition', 'attachment; filename="' . $nombreCSV . '"');

    while (!feof($file)) {
      $response->getBody()->write(fread($file, 9999));
    }

    fclose($file);

    return $response;
  }
}
