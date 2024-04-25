<?php

function conectarDB()
{

    $db = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);

    if ($db) {
        $db->set_charset('utf8');
        return $db;
    }
    echo 'Error al conectar en la base de datos';
}
