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
    public $nroPedido;


    public function crearVenta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO venta (nropedido, email, descripcion, tipo, talla, stock, fechaventa) VALUES (:nropedido, :email, :descripcion, :tipo, :talla, :stock, :fecha)");
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':talla', $this->talla, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
        $consulta->bindValue(':nropedido', $this->nroPedido, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idVenta, nroPedido, email, descripcion, tipo, talla, stock, fechaVenta FROM venta");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }


    public static function modificarPedido($nroPedido, $talla)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE venta SET talla = :talla WHERE nropedido = :nropedido");
        $consulta->bindValue(':talla', $talla, PDO::PARAM_STR);
        $consulta->bindValue(':nropedido', $nroPedido, PDO::PARAM_INT);
        $consulta->execute();
    }
}
