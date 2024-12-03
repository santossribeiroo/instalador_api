<?php

use util\ConstantesGenericasUtil;
use util\jsonUtil;
use util\rotasUtil;
use validator\requestValidator;

require_once ('config.php');

try {
    //chamar a função getRotas.
    $requestValidator = new requestValidator(rotasUtil::getRotas());

    //processar o request do validator
    $retorno = $requestValidator -> processarRequest();
    
    $jsonUtil = new jsonUtil();
    $jsonUtil -> retornoArray($retorno);
} catch (Exception $exception) {
    //throw $th;
    echo json_encode([
        ConstantesGenericasUtil::TIPO => ConstantesGenericasUtil::TIPO_ERRO,
        ConstantesGenericasUtil::RESPOSTA => $exception -> getMessage()
    ], JSON_THROW_ON_ERROR, 512);

    exit;
}