<?php

namespace Module;

class Pedidos extends ActiveRecord
{
    protected static $tablaName = 'pedidos';
    protected static $tablaDB = ['id', 'id_usuario', 'id_producto', 'id_estado', 'cantidad_total', 'precio_total', 'fecha'];

    public $id;
    public $id_usuario;
    public $id_producto;
    public $id_estado;
    public $cantidad_total;
    public $precio_total;
    public $fecha;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? '';
        $this->id_usuario = $args['id_usuario'] ?? '';
        $this->id_producto = $args['id_producto'] ?? '';
        $this->id_estado = $args['id_estado'] ?? 1;
        $this->cantidad_total = $args['cantidad_total'] ?? 0;
        $this->precio_total = $args['precio_total'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
    }

    public static function getPedidosAll()
    {
        $query = "SELECT pedidos.id , pedidos.cantidad_total, pedidos.precio_total,pedidos.fecha,pedidos.id_estado, CONCAT(usuario.nombre , ' ' , usuario.apellido) AS cliente ,CONCAT(usuario.correo) AS contacto, producto.nombre ,estado.estado FROM pedidos LEFT OUTER JOIN usuario ON pedidos.id_usuario = usuario.id LEFT OUTER JOIN estado ON pedidos.id_estado = estado.id LEFT OUTER JOIN producto ON pedidos.id_producto = producto.id;";

        try {
            $registros = self::$conectionDB->query($query);
            if ($registros->num_rows !== 0) {
                while ($registro = $registros->fetch_assoc()) {
                    $array[] = $registro;
                };
                return $array;
            };
        } catch (\Throwable $th) {
            self::$validacion['mensaje'] = $th;
        }
    }

    public static function getPedidosByWhere($id)
    {
        $query = "SELECT pedidos.id,pedidos.fecha,pedidos.cantidad_total,pedidos.precio_total,pedidos.id_estado,producto.precio,producto.nombre,producto.descripcion,producto.imagen,estado.estado FROM pedidos LEFT OUTER JOIN usuario ON pedidos.id_usuario = usuario.id
        LEFT OUTER JOIN producto ON pedidos.id_producto = producto.id
        LEFT OUTER JOIN estado ON pedidos.id_estado = estado.id
        WHERE usuario.id = $id;";

        try {
            $registros = self::$conectionDB->query($query);
            if ($registros->num_rows !== 0) {
                while ($registro = $registros->fetch_assoc()) {
                    $array[] = $registro;
                };
                return $array;
            };
        } catch (\Throwable $th) {
            self::$validacion['mensaje'] = $th;
        }
    }
}
