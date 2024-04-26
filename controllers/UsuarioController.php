<?php

namespace Controllers;

use Module\Usuario;
use Module\Producto;

class UsuarioController
{
    public static function registro()
    {
        $json = json_decode(file_get_contents('php://input'));
        if (!empty($json)) {
            try {
                $usuario = new Usuario();

                $usuario::validarFormulario($json);

                if (empty(Usuario::getValidacion())) {
                    $usuario->setAtributos($json);
                    $usuario->hashearPassword();
                    $usuario->setToken();
                    $arrayAtributos = $usuario->sanitizarAtributos();
                    $respuesta = $usuario::save($arrayAtributos);

                    if ($respuesta) {
                        $asunto = 'Confirmacion de Cuenta';
                        gmail($usuario, $asunto);
                    } else {
                        header($_SERVER["SERVER_PROTOCOL"] . " 500 Herror al registrar usuario");
                    }
                };

                if (!empty(Usuario::getValidacion())) {
                    $mensaje = Usuario::getValidacion();
                    send_json($json, 450);
                    return;
                };

                send_json('Usuario registrado Correctamente', 200);
            } catch (\Throwable $th) {
                send_json($th, 430);
            }
        };
    }

    public static function confirmar($token)
    {
        if ($token) {
            $usuario = Usuario::getByWhere('token', $token);


            if ($usuario) {
                $usuario->confirmado = true;
                $usuario->token = 0;
                $respuesta = Usuario::update($usuario->id, $usuario);
                if (!$respuesta) {
                    header($_SERVER["SERVER_PROTOCOL"] . " 500 Hubo un error al intentar guardar los datos");
                }
            } else {
                header($_SERVER["SERVER_PROTOCOL"] . " 404 Usuario no registrado");
            };

            send_json('Usuario confirmado correctamente', 200);
        };
    }

    public static function login()
    {
        if (!empty($_POST)) {
            Usuario::validarFormulario($_POST);

            if (empty(Usuario::getValidacion())) {
                $usuario = Usuario::getByWhere('correo', $_POST['correo']);

                if ($usuario) {
                    Usuario::verifayPassword($_POST['password'], $usuario->password);
                } else {
                    header($_SERVER["SERVER_PROTOCOL"] . " 404 Usuario no registrado");
                }
            };

            if (!empty(Usuario::getValidacion())) {
                $mensaje = Usuario::getValidacion();
                send_json($mensaje['mensaje'], 400);
                return;
            };

            $jwt = createJWT($usuario->id);
            $usuarioAuth['id'] = $usuario->id;
            $usuarioAuth['administrador'] = $usuario->administrador;
            send_json($usuarioAuth, 200, $jwt);
        };
    }

    public static function logoauth()
    {
    }
}
