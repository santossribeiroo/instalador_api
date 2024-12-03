<?php

namespace validator;

use repository\TokensAutorizadosRepository;
use service\clientService;
use util\ConstantesGenericasUtil;
use util\JsonUtil;

class requestValidator {

    const GET = 'GET';
    const DELETE = 'DELETE';
    const CLIENT = 'CLIENT';

    private array $request;
    private array $dadosRequest = [];
    private object $tokensAutorizadosRepository;

    public function __construct($request){

        $this -> request = $request;
        $this -> tokensAutorizadosRepository = new tokensAutorizadosRepository();
    }

    public function processarRequest() {

        $retorno = ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA;

        if(in_array($this -> request['metodo'], ConstantesGenericasUtil::TIPO_REQUEST, true)){
            $retorno = $this -> direcionarRequest();
        }

        return $retorno;
    }

    private function direcionarRequest(){
        //se for um método diferente de GET e DELETE, precisa do JSON (jsonUtil.php)
        if ($this -> request['metodo'] !== self::GET && $this -> request['metodo'] !== self::DELETE) {
            $this -> dadosRequest = JsonUtil::tratarJson();
        }

        $this -> tokensAutorizadosRepository -> validarToken(getallheaders()['Authorization']);
        $metodo = $this -> request['metodo'];
        return $this -> $metodo();
    }

    private function get(){
        $retorno = ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA;
        if(in_array($this -> request['rota'], ConstantesGenericasUtil::TIPO_GET)) {
            switch ($this -> request['rota']) {
                case self::CLIENT:
                    $clientService = new clientService($this->request);
                    $retorno = $clientService-> validarGet();
                    break;
                default:
                
                    throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $retorno;
    }

    private function delete(){
        $retorno = ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA;
        if(in_array($this -> request['rota'], ConstantesGenericasUtil::TIPO_DELETE)) {
            switch ($this -> request['rota']) {
                case self::CLIENT:
                    $clientService = new clientService($this->request);
                    $retorno = $clientService-> validarDelete();
                    break;
                default:
                    throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $retorno;
    }

    private function post(){
        $retorno = ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA;
        if(in_array($this -> request['rota'], ConstantesGenericasUtil::TIPO_POST)) {
            switch ($this -> request['rota']) {
                case self::CLIENT:
                    $clientService = new clientService($this->request);
                    $clientService -> setDadosRequest($this -> dadosRequest);
                    $retorno = $clientService-> validarPost();
                    break;
                default:
                    throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $retorno;
    }

    private function put()
    {
        $retorno = null;
        if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_PUT, true)) {
            switch ($this->request['rota']) {
                case self::CLIENT:
                    $clientService = new clientService($this->request);
                    $clientService->setDadosRequest($this->dadosRequest);
                    $retorno = $clientService->validarPut();
                    break;
                default:
                    throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
            }
            return $retorno;
        }
        throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
    }
}