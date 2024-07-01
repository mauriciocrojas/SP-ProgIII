<?php

require_once './models/Usuario.php';

class Login
{
    public function ProcesoIngreso($request, $response)
    {
        $existeUsuario = false;

        $parametros = $request->getParsedBody();

        if (isset($parametros['usuario'], $parametros['clave'])) {
            $listaUsuarios = Usuario::obtenerTodos();

            
            foreach ($listaUsuarios as $usuario) {
                if ($parametros['usuario'] == $usuario->usuario && $parametros['clave'] == $usuario->clave) {
                    $payload = json_encode(array("mensaje" => "Logueo exitoso"));
                    $existeUsuario = true;
                    break;
                } else {
                    $existeUsuario = false;
                }
            }
        } else {
            $payload = json_encode(array("mensaje" => "Faltan setear credenciales"));
            $existeUsuario = true;
        }

        if (!$existeUsuario) {
            $payload = json_encode(array("mensaje" => "Logueo invalido, datos erroneos"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    // public static function CrearToken(){
    //     $datos = "Lo devuelto por getParsedBody";
    //     $ahora = time();

    //     $payload = array (
    //         'iat' => $ahora,
    //         'data' => $datos,
    //         'app' => 'API REST 20240'
    //     );

    //     //Codifico a JWT (payload, clave, algoritmo de codificación)
    //     $token = JWT::encode($payload, 'miClaveSecreta', 'HS256');

    //     $newResponse = $response->withStatus(200, 'Exito! Json enviado');

    //     //Genero el json a partir del array
    //     $newResponse->getBody()->write(json_encode($token));

    //     return $newResponse->withHeader('Content-Type', 'application/json');
    // }

    // public static function VerificarToken(){
    //     $datos = "Lo devuelto por getParsedBody";//debería ser del header
    //     $token = $datos['token'];

    //     $retorno = new stdClass();
    //     $status = 200;

    //     try{
    //         //Decodifico el token recibido
    //         JWT::decode (
    //           $token,                   //JWT
    //           'miClaveSecreta',         //Clave usada en la creación
    //           ['HS256']                 //Algoritmo de codificación
    //         );
    //     }
    //     catch(Exception $e){
    //         $retorno->mensaje = "Token no valido! ---> " - $e->getMessage();
    //         $status = 500;
    //     }

    //     $newResponse = $response->withStatus($status);

    //     //Genero el json a partir del array
    //     $newResponse->getBody()->write(json_encode($retorno));

    //     return $newResponse->withHeader('Content-Type', 'application/json');
    // }

}
