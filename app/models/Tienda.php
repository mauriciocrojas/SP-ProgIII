<?php

class Tienda
{
    public $idPrenda;
    public $descripcion;
    public $tipo;
    public $color;
    public $talla;
    public $precio;
    public $stock;



    public function crearPrenda()
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO tienda (descripcion, tipo, color, talla, precio, stock) VALUES (:descripcion, :tipo, :color, :talla, :precio, :stock)");
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':color', $this->color, PDO::PARAM_STR);
        $consulta->bindValue(':talla', $this->talla, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function ActualizarPrendaExistente($stock, $precio, $idprenda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE tienda SET stock = stock + :stock, precio = :precio WHERE idprenda = :idprenda");

        $consulta->bindValue(':precio', $precio, PDO::PARAM_INT);
        $consulta->bindValue(':stock', $stock, PDO::PARAM_INT);
        $consulta->bindValue(':idprenda', $idprenda, PDO::PARAM_INT);

        $consulta->execute();
    }


    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idPrenda, descripcion, tipo, color, talla, precio, stock FROM tienda");

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Tienda');
    }

    public static function obtenerUno($idprenda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idPrenda, descripcion, tipo, color, talla, precio, stock FROM tienda WHERE idprenda = :idprenda");
        $consulta->bindValue(':idprenda', $idprenda, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Tienda');
    }

    public static function modificarPedido($id, $estado)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido SET estado = :estado WHERE idpedido = :idpedido");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':idpedido', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarPedido($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido SET estado = 'Baja' WHERE idpedido = :idpedido");

        $consulta->bindValue(':idpedido', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}
