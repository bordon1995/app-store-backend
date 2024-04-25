<?php

namespace Controllers;

use Module\Pedidos;

class ClienteController
{
    public static function mostrarClientes()
    {
        $registro = Pedidos::getPedidosAll();
        if ($registro) {
            send_json($registro, 200);
        }
    }
}
