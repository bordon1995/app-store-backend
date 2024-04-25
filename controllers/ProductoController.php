<?php

namespace Controllers;

use Module\Producto;

class ProductoController
{
    public static function mostrar()
    {

        $productos = Producto::all();
        send_json($productos, 200);
    }

    public static function perfil()
    {
        send_json($_SESSION, 200);
    }

    public static function agregar()
    {
        if (!empty($_POST)) {

            $producto = new Producto();

            Producto::validarFormulario($_POST);

            if (empty($producto::getValidacion())) {
                $producto->setAtributos($_POST);
                $producto->usuario_id = $_SESSION['id'];
                $atributos = $producto->sanitizarAtributos();
                $producto::save($atributos);
            }

            if (!empty($producto::getValidacion())) {
                echo json_encode($producto::getValidacion());
            }
            echo json_decode('Producto Agregado');
        };
    }

    public static function obtener($id)
    {
        $producto = Producto::getByWhere('id', $id);
        send_json($producto, 200);
    }

    public static function eliminar($id)
    {
        Producto::delete('id', $id);
        send_json('Producto eliminado', 200);
    }
}
