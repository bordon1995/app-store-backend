<?php

namespace Module;

use Error;
use Exception;

abstract class ActiveRecord
{
    protected static $tablaName;
    protected static $conectionDB;
    protected static $tablaDB = array();
    protected static $validacion = array();

    public static function getValidacion()
    {
        return self::$validacion;
    }

    public static function setConectarDB($db)
    {
        self::$conectionDB = $db;
    }

    public function setAtributos($array)
    {

        foreach (static::$tablaDB as $tabla) {
            foreach ($array as $key => $value) {
                if ($tabla === $key) {
                    $this->$tabla = $value;
                };
            };
        };
    }

    public function getAtributos()
    {
        $atributos = [];
        foreach (static::$tablaDB as $tabla) {
            if ($tabla === 'id') continue;
            $atributos[$tabla] = $this->$tabla;
        };
        return $atributos;
    }

    public static function validarFormulario($_post)
    {
        foreach ($_post as $key => $value) {
            if ($value === '') {
                if (!isset(self::$validacion['mensaje'])) {
                    self::$validacion['mensaje'] = 'Todos los campos son obligatorios';
                };
            }
            if ($key === 'correo') {
                if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
                    self::$validacion['mensaje'] = 'El correo no es valido';
                };
            };
        };
    }

    public function sanitizarAtributos()
    {
        $atributos = $this->getAtributos();

        foreach ($atributos as $key => $value) {
            $atributos[$key] = self::$conectionDB->real_escape_string($value);
        };
        return $atributos;
    }

    public static function save($array)
    {
        $query = "INSERT INTO " . static::$tablaName . " ( ";
        $query .= join(',', array_keys($array));
        $query .= " ) VALUES ( '";
        $query .= join("','", array_values($array));
        $query .= "' ) ;";

        try {
            $respuesta = self::$conectionDB->query($query);
            if ($respuesta) {
                return true;
            }
        } catch (\Throwable $th) {
            self::$validacion['mensaje'] = $th;
        }
    }

    public static function getByWhere($columna, $value, $especificidad = [] ?? null)
    {

        if (!empty($especific)) {
            foreach ($especificidad as $table) {
                $array[] = static::$tablaName . "." . $table;
            }
            $query = "SELECT " . join(',', $array) . " FROM " . static::$tablaName . " WHERE $columna = $value;";
        } else {
            $query = "SELECT * FROM " . static::$tablaName . " WHERE $columna = '$value';";
        };


        $resultado = self::queryDB($query);

        if ($resultado) {
            return array_pop($resultado);
        } else {
            return false;
        }
    }

    public static function all()
    {
        $query = "SELECT * FROM " . static::$tablaName;

        $resultado = self::queryDB($query);
        if ($resultado) {
            return $resultado;
        } else {
            false;
        }
    }

    public static function delete($id)
    {
        $query = "DELETE FROM " . static::$tablaName . " WHERE id = $id ;";

        self::$conectionDB->query($query);
    }

    public static function update($id, $object)
    {
        $array = [];
        foreach ($object as $key => $value) {
            $array[] = "{$key}='{$value}'";
        };

        $array = implode(',', $array);

        $query = "UPDATE " . static::$tablaName . " SET {$array} WHERE id = {$id};";

        $respuesta = self::$conectionDB->query($query);
        if ($respuesta) {
            return true;
        };
        return false;
    }

    public static function queryDB($query)
    {
        $registros = self::$conectionDB->query($query);

        if ($registros->num_rows !== 0) {
            while ($registro = $registros->fetch_assoc()) {
                $array[] = self::crearObject($registro);
            };
            return $array;
        };
    }

    public static function crearObject($array)
    {
        $cliente = new static;
        foreach ($array as $key => $value) {
            if (property_exists($cliente, $key)) {
                $cliente->$key = $value;
            };
        };
        return $cliente;
    }
}
