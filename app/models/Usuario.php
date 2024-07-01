<?php

class Usuario
{
    public $idUsuario;
    public $email;
    public $nombreUsuario;
    public $clave;
    public $perfil;
    public $ubicacionFoto;
    public $fechaAlta;
    public $fechaBaja;


    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuario (email, usuario, clave, perfil, ubicacionfoto, fechaalta) 
                                                                    VALUES (:email,:usuario, :clave, :perfil, :ubicacionfoto, :fechaalta)");
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':usuario', $this->nombreUsuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
        $consulta->bindValue(':ubicacionfoto', $this->ubicacionFoto, PDO::PARAM_STR);
        $consulta->bindValue(':fechaalta', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    
    }
    

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idusuario, email, usuario, clave, perfil, ubicacionfoto, fechaalta, fechabaja FROM usuario");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);

    }


}
