<?php

class Venta
{
    public $idVenta;
    public $email;
    public $descripcion;
    public $tipo;
    public $stock;
    public $fechaVenta;
    public $talla;


    public function crearVenta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO venta (email, descripcion, tipo, talla, stock, fechaventa) VALUES (:email, :descripcion, :tipo, :talla, :stock, :fecha)");
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':talla', $this->talla, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idproducto, descripcion, estado, tipo, tiempopreparacion, precio FROM producto");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProducto($descripcion)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idproducto, descripcion, estado, tipo, tiempopreparacion, precio FROM producto WHERE descripcion = :descripcion");
        $consulta->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function modificarProducto($id, $estado)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE producto SET estado = :estado WHERE idproducto = :idproducto");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':idproducto', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarProducto($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE producto SET estado = 'Baja' WHERE idproducto = :idproducto");

        $consulta->bindValue(':idproducto', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}
