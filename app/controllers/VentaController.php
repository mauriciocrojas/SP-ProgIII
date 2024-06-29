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
        $rutaImagen = "../ImagenesVentas2024/" . $descripcion . $tipo . $talla . $nombreUsuario. $fechaYTipo;
  
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
    $payload = json_encode(array("listaProductos" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    // Buscamos producto por su descripcion
    $descripcionProducto = $args['descripcionProducto'];
    $producto = Venta::obtenerProducto($descripcionProducto);
    $payload = json_encode($producto);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $id = $args['id'];
    $estado = $parametros['estado'];
    Venta::modificarProducto($id, $estado);

    $payload = json_encode(array("mensaje" => "Estado del producto modificado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $id = $args['id'];
    Venta::borrarProducto($id);

    $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
