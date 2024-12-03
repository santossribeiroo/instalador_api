<?php

namespace repository;

use db\MySQL;
use util\ConstantesGenericasUtil;

class clientRepository
{
    private object $MySQL;
    public const TABELA = 'machine';

    /**
     * clientRepository constructor.
     */
    public function __construct(){
        $this -> MySQL = new MySQL();
    }

    public function insertInfo($adm, $user, $ipv4, $macaddress, $hostname, $dtcadastro) {
        $consultaInsert = 'INSERT INTO ' . self::TABELA . ' 
        (ADM, USER, IPV4, MACADDRESS, HOSTNAME, DTCADASTRO) 
        VALUES (:adm, :user, :ipv4, :macaddress, :hostname, :dtcadastro)';
        

        $this -> MySQL ->getDb() -> beginTransaction();
        $stmt = $this -> MySQL ->getDb() -> prepare($consultaInsert);
        $stmt -> bindParam(':adm', $adm);
        $stmt -> bindParam(':user', $user);
        $stmt -> bindParam(':ipv4', $ipv4);
        $stmt -> bindParam(':macaddress', $macaddress);
        $stmt -> bindParam(':hostname', $hostname);
        $stmt -> bindParam(':dtcadastro', $dtcadastro);
        $stmt -> execute();
        return $stmt -> rowCount();

    }

    public function getMySQL(){
        return $this -> MySQL;
    }
}