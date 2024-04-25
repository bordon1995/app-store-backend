<?php

namespace Module;

class Usuario extends ActiveRecord
{
    protected static $tablaName = 'usuario';
    protected static $tablaDB = ['id', 'nombre', 'apellido', 'correo', 'password', 'administrador', 'token', 'confirmado'];

    public $id;
    public $nombre;
    public $apellido;
    public $correo;
    public $password;
    public $administrador;
    public $token;
    public $confirmado;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->correo = $args['correo'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->administrador = $args['administrador'] ?? 0;
        $this->token = $args['token'] ?? 0;
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    public function hashearPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function setToken()
    {
        $this->token = uniqid();
    }

    public static function verifayPassword($passwordInput, $passwordDB)
    {
        $resultado = password_verify($passwordInput, $passwordDB);

        if (!$resultado) {
            self::$validacion['mensaje'] = 'Contraseña incorrecta';
        };
    }
}
