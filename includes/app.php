<?php

use Dotenv\Dotenv;
use Module\ActiveRecord;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../confi/db.php';
require __DIR__ . '/gmail.php';
require __DIR__ . '/helpers.php';
require __DIR__ . '/jwt.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$conectionDB = conectarDB();

ActiveRecord::setConectarDB($conectionDB);
