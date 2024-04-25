<?php

namespace Router;

use Module\Usuario;

class Router
{
    public $privateRout = ['/api/home', '/api/perfil', '/api/pedido/agregar', '/api/pedido', '/api/editar/pedido', '/api/eliminar/pedido', '/api/cliente', 'api/prueva'];
    public $routerGET = array();
    public $routerPOST = array();

    public function get($uri, $function)
    {
        $this->routerGET[$uri] = $function;
    }

    public function post($uri, $function)
    {
        $this->routerPOST[$uri] = $function;
    }

    public function middelWare()
    {
        $uriActual = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $this->verifyParams($this->routerGET, $uriActual);
        } else {
            $this->verifyParams($this->routerPOST, $uriActual);
        };
    }

    public function verifyParams($routs, $uriActual)
    {

        $isSession = in_array($uriActual, $this->privateRout);

        if ($isSession) {
            $success = $this->isSession();
            if ($success) {
                $this->getFunction($routs, $uriActual);
            }
        } else {
            $this->getFunction($routs, $uriActual);
        }
    }

    public function isSession()
    {
        if ($_SERVER['HTTP_AUTHORIZATION']) {
            $var = strtok($_SERVER['HTTP_AUTHORIZATION'], " ");
            $token = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
            if ($var === 'Bearer') {
                try {
                    $respuesta = validateJWT($token[1], $_ENV['JWT_KEY']);
                    $usuario = Usuario::getByWhere('id', $respuesta->id, ['id', 'nombre', 'apellido']);
                    if ($respuesta && $usuario->id) {
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['administrador'] = $usuario->administrador;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['apellido'] = $usuario->apellido;

                        return true;
                    }
                } catch (\Throwable $th) {
                    $_SESSION = [];
                    header($_SERVER["SERVER_PROTOCOL"] . " 404 Usuario o tokenn no validos");
                    return false;
                }
            };
        } else {
            send_json('Autenticacion no establecida o inexistaente', 404);
        };
    }

    public function getFunction($routs, $uriActual)
    {
        foreach ($routs as $key => $value) {

            if (strpos($key, ':') !== false) {
                $key = preg_replace('#:[a-z0-9]+#', '([a-z0-9]+)', $key);

                if (preg_match("#^$key$#", $uriActual, $matches)) {
                    $params = array_slice($matches, 1);
                    $value[0]::{$value[1]}(...$params);
                    return;
                };
            };
        };

        $callback = $routs[$uriActual];
        $this->callBack($callback);
    }

    public function callBack($callback)
    {
        if (!empty($callback)) {
            call_user_func($callback, $this);
        } else {
            echo 'pagina no encontrada';
        };
    }
}
