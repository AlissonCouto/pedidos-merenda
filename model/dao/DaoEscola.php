<?php
/**
 * Created by PhpStorm.
 * User: alissoncouto
 * Date: 27/08/2019
 * Time: 13:45
 */

include_once 'Sql.php';

class DaoEscola
{

    private $con;

    /**
     * Método de busca por id
     * @param mixed $id
     */
    public function getById($id){

        $id = (int) $id;
        $sql = new Sql();

        $e = $sql->select("SELECT * FROM tbescola 
                       WHERE id = :id", array(":id" => $id));

        $returnEscola = $e->fetchObject("Escola");


        $returnEscola->setEndereco($this->getEndereco($returnEscola->getId()));

        return $returnEscola;
    }

    /**
     * Método de buscar todos
     */
    public function getAll(){

        $sql = new Sql();

        $returnEscola = $sql->select("SELECT * FROM tbescola");

        foreach($returnEscola as $es){

            $es->setEndereco($this->getEndereco($es->id));

        }

        return $returnEscola;
    }

    public function select($rawQuery, $parameters = []){
        $sql = new Sql();

        $esc = $sql->query($rawQuery, $parameters)->fetchAll(PDO::FETCH_CLASS, 'Escola');

        foreach($esc as $v){
            $v->setEndereco($this->getEndereco($v->idEndereco));
        }

        return $esc;
    }

    public function listagemRelatorio($idPedido){
        $sql = new Sql();
        $escolas = $sql->query("SELECT distinct esc.id, esc.nome, esc.modalidade, esc.telefone, esc.idEndereco, esc.alunos FROM tbItemPedido iped 
                                    JOIN tbEscola esc ON iped.idEscola = esc.id
                                WHERE idPedido = :idPedido", [":idPedido" => $idPedido])->fetchAll(PDO::FETCH_CLASS, "Escola");
        foreach($escolas as $escola){

            $end = $sql->query("SELECT * FROM tbEndereco WHERE id = :id", [":id" => $escola->idEndereco])->fetchObject("Endereco");
            $escola->setEndereco($end);
        }

        return $escolas;
    }
    
    public function getEndereco($id){
        $daoE = new DaoEndereco();
        return $daoE->getById($id);
    }

}