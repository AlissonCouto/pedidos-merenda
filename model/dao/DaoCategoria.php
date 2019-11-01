<?php
/**
 * Created by PhpStorm.
 * User: alissoncouto
 * Date: 27/08/2019
 * Time: 13:45
 */

include_once 'Sql.php';

class DaoCategoria
{

    /**
     * Método de busca por id
     * @param mixed $id
     */
    public function getById($id){

        $id = (int) $id;
        $sql = new Sql();

        $m = $sql->select("SELECT * FROM tbCategoria 
                       WHERE id = :id", array(":id" => $id));

        $returnCategoria = $m;

        return  $returnCategoria;
    }

    /**
     * Método de buscar todos
     */
    public function getAll(){

        $sql = new Sql();

        $returnCategoria = $sql->select("SELECT * FROM tbCategoria");


        return $returnCategoria;
    }

    public function select($rawQuery, $parameters = []){
        $sql = new Sql();

        $mod = $sql->query($rawQuery, $parameters)->fetchAll(PDO::FETCH_CLASS, 'Categoria');

        return $mod;
    }

}