<?php

require_once __DIR__ . '/../includes/app.php';

use Controllers\ClienteController;
use Router\Router;
use Controllers\UsuarioController;
use Controllers\ProductoController;
use Controllers\PedidosController;

header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:Origin,X-Requested-With,Content-Type,Authorization,Accept');
header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS');
header('content-type: application/json');


$router = new Router();
//RUTAS-PUBLICAS
//login
$router->post('/api', [UsuarioController::class, 'login']);
//registro
$router->post('/api/registro', [UsuarioController::class, 'registro']);
//confirmar-cuenta
$router->get('/api/confirmar-cuenta/:token', [UsuarioController::class, 'confirmar']);
//logoauth
$router->post('/api/logoauth', [UsuarioController::class, 'logoauth']);

//RUTAS-PRIVADAS

//listar-productos
$router->get('/api/home', [ProductoController::class, 'mostrar']);
//cuenta-perfil
$router->get('/api/perfil', [ProductoController::class, 'perfil']);
//obtener-mis-pedidos
$router->post('/api/pedido', [PedidosController::class, 'mostrarPedidos']);
//agregar-pedido
$router->post('/api/pedido/agregar', [PedidosController::class, 'agregarPedido']);
//editar-pedido
$router->post('/api/editar/pedido', [PedidosController::class, 'editar']);
//eliminar
$router->post('/api/eliminar/pedido', [PedidosController::class, 'eliminar']);
//ADMINISTRADOR
//obtener-mis-clientes
$router->get('/api/cliente', [ClienteController::class, 'mostrarClientes']);

$router->middelWare();
