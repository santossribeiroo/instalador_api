<?php

namespace service;

use repository\clientRepository;
use util\ConstantesGenericasUtil;

class clientService
{
    public const TABELA = 'machine';
    public const RECURSOS_GET = ['listar', 'listarAdm'];
    public const RECURSOS_POST = ['cadastrar'];
    public const RECURSOS_DELETE = ['deletar'];
    public const RECURSOS_PUT = ['atualizar'];

    private array $dados;
    private array $dadosbodyRequest = [];

    private object $clientRepository;

    public function __construct($dados = []){
        $this->dados = $dados;
        $this->clientRepository = new clientRepository();
    }

    public function validarGet(){

        $retorno = null;
        $recurso = $this->dados['recurso'];
        if (in_array($recurso, self::RECURSOS_GET, true)) {
            $retorno = $this -> dados['id'] > 0 ? $this -> getOneByKey() : $this ->$recurso();
        } else {
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($retorno === null) {
            
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        return $retorno;
    }

    public function validarDelete(){

        $retorno = null;
        $recurso = $this->dados['recurso'];
        if (in_array($recurso, self::RECURSOS_DELETE, true)) {
            if ($this -> dados['id'] > 0) {
                $retorno = $this -> $recurso();
            } else {
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
            }
        } else {
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($retorno === null) {
            
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        return $retorno;
    }

    public function validarPost(){

        $retorno = null;
        $recurso = $this->dados['recurso'];
        if (in_array($recurso, self::RECURSOS_POST, true)) {
            $retorno = $this -> $recurso();
        } else {
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($retorno === null) {
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        return $retorno;
    }

    public function validarPut(){
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if (in_array($recurso, self::RECURSOS_PUT, true)) {
            if ($this->dados['id'] > 0) {
                $retorno = $this->$recurso();
            } else {
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
            }
        } else {
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($retorno === null) {
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        return $retorno;
    }

    public function setDadosRequest($dadosbodyRequest){
        $this -> dadosbodyRequest = $dadosbodyRequest;
    }

    private function getOneByKey(){
        return $this -> clientRepository -> getMySQL() -> getOneByKey(self::TABELA, $this -> dados['id']);
    }

    private function listar() {
        return $this -> clientRepository -> getMySQL() -> getAll(self::TABELA);
    }

    private function listarAdm(){
        return $this -> clientRepository -> getMySQL() -> getAllByAdm(self::TABELA, $this -> dados['adm']);
    }

    private function deletar(){
        return $this -> clientRepository -> getMySQL() -> delete(self::TABELA, $this -> dados['id']);
    }

    private function cadastrar(){
        [   $adm, $user, $ipv4, $macaddress, $hostname, $dtcadastro ] =
        [   
            $this -> dadosbodyRequest['ADM'],
            $this -> dadosbodyRequest['USER'],
            $this -> dadosbodyRequest['IPV4'],
            $this -> dadosbodyRequest['MACADDRESS'],
            $this -> dadosbodyRequest['HOSTNAME'],
            $this -> dadosbodyRequest['DTCADASTRO']

        ];
        
        if ($adm && $user && $ipv4 && $macaddress && $hostname && $dtcadastro) {
            if($this -> clientRepository -> 
            insertInfo($adm, $user, $ipv4, $macaddress, $hostname, $dtcadastro) > 0 ) {
                $idInserido = $this -> clientRepository -> getMySQL() -> getDb() -> lastInsertId();
                $this -> clientRepository -> getMySQL() -> getDb() -> commit();
                return ['id_inserido' => $idInserido]; 
            }

            $this -> clientRepository -> getMySQL() -> getDb() -> rollBack();

            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }
        throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_INFO_OBRIGATORIO);
    }

    private function atualizar(){
        if ($this->clientRepository->updateUser($this->dados['id'], $this->dadosBodyRequest) > 0) {
            $this->clientRepository->getMySQL()->getDb()->commit();
            return ConstantesGenericasUtil::MSG_ATUALIZADO_SUCESSO;
        }
        $this->clientRepository->getMySQL()->getDb()->rollBack();
        throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_NAO_AFETADO);
    }
}