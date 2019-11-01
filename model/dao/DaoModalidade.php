<?php
/**
 * Created by PhpStorm.
 * User: alissoncouto
 * Date: 27/08/2019
 * Time: 13:45
 */

include_once 'Sql.php';

class DaoModalidade
{

    /**
     * Método de busca por id
     * @param mixed $id
     */
    public function getById($id){

        $id = (int) $id;
        $sql = new Sql();

        $m = $sql->select("SELECT * FROM tbModalidade 
                       WHERE id = :id", array(":id" => $id));

        $returnModalidade = $m->fetchObject("Modalidade");

        return $returnModalidade;
    }

    /**
     * Método de buscar todos
     */
    public function getAll(){

        $sql = new Sql();

        $returnModalidade = $sql->select("SELECT * FROM tbModalidade");


        return $returnModalidade;
    }

    public function select($rawQuery, $parameters = []){
        $sql = new Sql();

        $mod = $sql->query($rawQuery, $parameters)->fetchAll(PDO::FETCH_CLASS, 'Modalidade');

        return $mod;
    }

}