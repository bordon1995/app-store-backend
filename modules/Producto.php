<?php

namespace Module;

class Producto extends ActiveRecord
{
    protected static $tablaName = 'producto';
    protected static $tablaDB = ['id', 'nombre', 'precio', 'imagen', 'cantidad', 'disponibilidad'];

    public $id;
    public $nombre;
    public $precio;
    public $imagen;
    public $disponibilidad;
    public $descripcion;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->disponibilidad = $args['disponibilidad'] ?? 0;
        $this->descripcion = $args['descripcion'] ?? '';
    }
}
