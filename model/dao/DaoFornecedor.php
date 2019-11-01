<?php
/**
 * Created by PhpStorm.
 * User: alissoncouto
 * Date: 27/08/2019
 * Time: 13:45
 */

include_once 'Sql.php';
include_once 'DaoEndereco.php';

class DaoFornecedor
{

    private $con;

    /**
     * Método de busca por id
     * @param mixed $id
     */
    public function getById($id){

        $id = (int) $id;
        $sql = new Sql();

        $busca = $sql->query("SELECT * FROM tbFornecedor 
                       WHERE id = :id", array(":id" => $id));

        $returnFornecedor = $busca->fetchObject("Fornecedor");

        $DaoEnd = new DaoEndereco();

        $end = $DaoEnd->getById($returnFornecedor->idEndereco);

        $returnFornecedor->setEndereco($end);

        return $returnFornecedor;
    }

    /**
     * Método de buscar todos PARÂMETRO end: boolean para retornar objeto com endereço
     */
    public function getAll($end = false){

        $sql = new Sql();

        $returnFornecedor = $sql->select("SELECT * FROM tbFornecedor");

        if($end){
            $daoE = new DaoEndereco();

            foreach($returnFornecedor as $for){

                $for->setEndereco($daoE->get($for->id));

            }
        }

        return $returnFornecedor;
    }

    /**
     * Método para contar a quantidade de fornecedores em determinado pedido
     */
    public function pegarFornecedoresPedido($idPedido){

        $idPedido = (int) $idPedido;

        $sql = new Sql();

        $fornecedores = $sql->select("SELECT distinct forn.id, forn.razaosocial, forn.cnpj, ende.bairro, ende.rua, ende.numero FROM tbItemPedido iped
	                                      JOIN tbProduto prod ON prod.id = iped.idProduto
                                          JOIN tbFornecedor forn ON prod.idFornecedor = forn.id
                                          JOIN tbEndereco ende ON forn.idEndereco = ende.id
	                                      WHERE (iped.idPedido = :idPedido);", [":idPedido" => $idPedido]);


        return $fornecedores;

    }

    /**
     * Método para buscar fornecedores com condições -> PARÂMETRO end: boolean para retornar objeto com endereço
     */
    public function select($rawQuery, $parameters = [], $end = false){
        $sql = new Sql();

        $for = $sql->query($rawQuery, $parameters)->fetchAll(PDO::FETCH_CLASS, 'Escola');

        if($end){
            foreach($for as $v){
                $v->setEndereco($this->getEndereco($v->idEndereco));
            }
        }

        return $for;
    }

    /**
     * Método para bucar endereço
     * */
    public function getEndereco($id){
        $daoE = new DaoEndereco();
        return $daoE->getById($id);
    }

}