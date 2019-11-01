<?php
/**
 * Created by PhpStorm.
 * User: alissoncouto
 * Date: 27/08/2019
 * Time: 13:13
 */

include_once 'Sql.php';
include_once 'DaoItemPedido.php';

class DaoPedido
{
    /**
     * Método de busca por id
     * @param mixed $id
     */
    public function insert(Pedido $pedido){

        $sql = new Sql();
        
        $insert = $sql->query("INSERT INTO tbPedido(
          numero, data_cad, idModalidade) VALUES (:numero, :data_cad, :idModalidade)
        ", [":numero" => $pedido->getNumero(), ":data_cad" => date('y-m-d'), ":idModalidade" => $pedido->getIdModalidade()]);


        if($insert){
            return $this->getLastId();
        }else{
            return false;
        }

    }

    /* BUSCAR NÚMERO DO ÚLTIMO PEDIDO */
    public function getLastNumber(){
    
       $sql = new Sql();
    
       $busca = $sql->select("SELECT max(numero) as id FROM tbPedido");
    
        return (int) $busca[0]->id;
    
    }

    public function getLastId(){

        $sql = new Sql();

        $busca = $sql->select("SELECT max(id) as id FROM tbPedido");

        return (int) $busca[0]->id;

    }

    /**
     * Método de buscar todos
     */
    public function getAll(){

        $sql = new Sql();

        $returnPedido = $sql->select("SELECT * FROM tbPedido");


        return $returnPedido;
    }

    public function select($rawQuery, $parameters = []){
        $sql = new Sql();

        $ped = $sql->query($rawQuery, $parameters)->fetchAll(PDO::FETCH_CLASS, 'Pedido');

        return $ped;
    }

    public function count($categoria){

        $sql = new Sql();

        $busca = $sql->select("SELECT count(id) as total FROM tbPedido");

        return (int) $busca[0]->total;

    }
    
    public function buscarFornecedoresPedido($idPedido){

        $idPedido = (int) $idPedido;

        $sql = new Sql();

        $fornecedores = $sql->query("SELECT distinct forn.razaosocial, forn.id, forn.razaosocial, forn.cnpj, forn.idendereco FROM tbItemPedido iped
                                        JOIN tbProduto prod ON iped.idProduto = prod.id
                                        JOIN tbFornecedor forn ON prod.idFornecedor = forn.id
                                        JOIN tbEndereco ende ON forn.idEndereco = ende.id
	                            WHERE (iped.idPedido = :idPedido);", [":idPedido" => $idPedido]);

        $fornecedores = $fornecedores->fetchAll(PDO::FETCH_CLASS, "Fornecedor");

        // Alimentar endereços
        foreach($fornecedores as $fornecedor){
             
            $endereco = $sql->query("SELECT * FROM tbEndereco WHERE id = :id", [":id" => $fornecedor->idendereco]);
            $endereco = $endereco->fetchObject("Endereco");
            $fornecedor->setEndereco($endereco);
        }
        
        return $fornecedores;

    }
    
    public function listaProdutos($idFornecedor, $categoria){
        $idFornecedor = (int) $idFornecedor;
        $categoria = (int) $categoria;

        $sql = new Sql();
        
        $produtos = $sql->query("SELECT distinct prod.descricao, prod.id, prod.numero, prod.marca, prod.idCategoria, prod.unidade FROM tbItemPedido iped
                JOIN tbProduto prod ON iped.idProduto = prod.id
                JOIN tbFornecedor forn ON prod.idFornecedor = forn.id
                 WHERE (prod.idFornecedor = :idFornecedor) AND (prod.idCategoria = :categoria)",
                [":idFornecedor" => $idFornecedor, ":categoria" => $categoria]);
        
        $produtos = $produtos->fetchAll(PDO::FETCH_CLASS, "Produto");
        
        return $produtos;
    }
    
    public function listaEscolas($idPedido){
        $idPedido = (int) $idPedido;
        
        $sql = new Sql();
        
        $escolas = $sql->query("
                            SELECT distinct esc.id, esc.nome, esc.modalidade, esc.telefone, esc.idEndereco, esc.alunos FROM tbEscola esc
                                JOIN tbItemPedido iped ON iped.idEscola = esc.id
                            WHERE iped.idPedido = :idPedido ORDER BY esc.nome ASC
                        ", [":idPedido" => $idPedido]);
        
        $escolas = $escolas->fetchAll(PDO::FETCH_CLASS, "Escola");
        
        return $escolas;
    }

}		