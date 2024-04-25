<?php

function debuger($variable)
{
    echo '<pre>';
    var_dump($variable);
    echo '</pre>';

    exit;
}

function sanitizarHTML($input)
{
    $html = htmlspecialchars($input);

    return $html;
}

function send_json($msg, $status, $token = null)
{
    if ($token === null) {
        $respuesta =
            [
                'estado' => $status,
                'respuesta' => $msg
            ];
    } else {
        $respuesta =
            [
                'estado' => $status,
                'respuesta' => $msg,
                'token' => $token
            ];
    }
    echo json_encode($respuesta);
}
