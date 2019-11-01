<?php
/**
 * Created by PhpStorm.
 * User: alissoncouto
 * Date: 27/08/2019
 * Time: 13:13
 */

include_once 'Sql.php';
require_once 'DaoProduto.php';

class DaoItemPedido
{
    /**
     * Método de buscar todos
     */
    public function getAll(){

        $sql = new Sql();

        $returnProduto = $sql->select("SELECT * FROM tbProduto");

        $daoFornecedor = new DaoFornecedor();

        foreach($returnProduto as $prod){

            $prod->setFornecedor($daoFornecedor->getById($prod->id));

        }

        return $returnProduto;
    }

    public function select($rawQuery, $parameters = []){
        $sql = new Sql();

        $prod = $sql->query($rawQuery, $parameters)->fetchAll(PDO::FETCH_CLASS, 'ItemPedido');

        return $prod;
    }

}