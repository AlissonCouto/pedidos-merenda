<?php
/**
 * Created by PhpStorm.
 * User: alissoncouto
 * Date: 27/08/2019
 * Time: 13:46
 */

include_once 'Sql.php';

class DaoEndereco
{

    /**
     * Método de busca por id
     * @param mixed $id
     */
    public function getById($id){

        $id = (int) $id;
        $sql = new Sql();

        $end = $sql->query("SELECT * FROM tbEndereco 
                       WHERE id = :id
                       ", array(":id" => $id));

        return $end->fetchObject("Endereco");
    }

    /**
     * Método de buscar todos
     */
    public function get(){

        $sql = new Sql();

        $end = $sql->select("SELECT * FROM tbEndereco");

        return $end;
    }

}