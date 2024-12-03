<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ERROR);

//constantes banco
const HOST = 'localhost';
const USER = 'xande';
const PASS = 'xande';
const DB = 'clients';
const PORT = '3306';

//constantes DIR
const DS = DIRECTORY_SEPARATOR;
const DIR_APP = __DIR__;
const DIR_PROJETO = 'instalador_api';

//carrega autoload
if (file_exists('autoload.php')) {
    include 'autoload.php';
} else {
    die('Falha ao carregar autoload!');
}