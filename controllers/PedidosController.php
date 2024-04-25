<?php

namespace Controllers;

use Module\Pedidos;

class PedidosController
{
    public static function agregarPedido()
    {
        $json = json_decode(file_get_contents('php://input'));

        if (!empty($json)) {

            $pedido = new Pedidos();

            foreach ($json as $array) {

                $pedido->setAtributos($array);
                $atributos = $pedido->sanitizarAtributos();
                $pedido::save($atributos);

                if (!empty($pedido::getValidacion())) {
                    debuger($pedido::getValidacion());
                    echo json_encode($pedido::getValidacion());
                }
            }
            send_json('Pedidos registrados correctamente', 200);
        };
    }

    public static function mostrarPedidos()
    {
        $json = json_decode(file_get_contents('php://input'));

        if (!empty($json)) {
            $registro = Pedidos::getPedidosByWhere($json->id);
            if (!empty(Pedidos::getValidacion())) {
                $mensaje = Pedidos::getValidacion();
                send_json($mensaje['mensaje'], 400);
            } else {
                send_json($registro, 200);
            }
        }
    }

    public static function editar()
    {
        $json = json_decode(file_get_contents('php://input'));

        if (!empty($json)) {

            $pedido = Pedidos::getByWhere('id', $json->id);

            if ($pedido->id_usuario !== $_SESSION['id']) {
                send_json('Accion invalida', 400);
                return;
            };

            if (empty($pedido::getValidacion())) {
                $pedido->setAtributos($json);
                $atributos = $pedido->sanitizarAtributos();
                $pedido::update($pedido->id, $atributos);
                send_json('Actualizado correctamente', 200);
            };
        };
    }

    public static function eliminar()
    {
        $json = json_decode(file_get_contents('php://input'));
        if (!empty($json)) {
            Pedidos::delete($json->id);
            send_json('Producto eliminado', 200);
        }
    }
}
