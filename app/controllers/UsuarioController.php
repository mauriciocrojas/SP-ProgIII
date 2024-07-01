<?php
require_once './models/Usuario.php';

class UsuarioController extends Usuario
{

  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();


    $email = $parametros['email'];
    $nombreUsuario = $parametros['usuario'];
    $clave = $parametros['clave'];
    $perfil = $parametros['perfil'];


    $archivosCargados = $request->getUploadedFiles();
    $fotousuario = $archivosCargados['fotousuario'];

    // Nuevo nombre archivo
    $nuevoNombreImagen = $nombreUsuario . '_' . $perfil . '_' . date("d-m-Y") . ".jpg";

    // Ruta a la que mandaremos el archivo
    $rutaImagen = "../ImagenesDeUsuarios2024/" . $nuevoNombreImagen;

    // Guardo el archivo
    $fotousuario->moveTo($rutaImagen);

    // Guadamos la ubicación de la imagen en la base de datos
    // Creamos el usuario
    $usuario = new Usuario();
    $usuario->email = $email;
    $usuario->nombreUsuario = $nombreUsuario;
    $usuario->clave = $clave;
    $usuario->perfil = $perfil;
    $usuario->ubicacionFoto = $rutaImagen;
    $usuario->crearUsuario();

    $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
      $lista = Usuario::obtenerTodos();
      $payload = json_encode(array("listaUsuarios" => $lista));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
  }
}
