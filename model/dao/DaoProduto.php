<?php
/**
 * Created by PhpStorm.
 * User: alissoncouto
 * Date: 27/08/2019
 * Time: 13:13
 */

include_once 'Sql.php';
include_once 'DaoFornecedor.php';
include_once 'DaoCategoria.php';

class DaoProduto
{

    /**
     * Método de busca por id
     * @param mixed $id
     */
    public function getById($id)
    {

        $id = (int)$id;
        $sql = new Sql();
        $daoCategoria = new DaoCategoria();


        $busca = $sql->query("SELECT * FROM tbProduto 
                       WHERE id = :id", array(":id" => $id));

        $returnProduto = $busca->fetchObject("Produto");
        $returnProduto->setCategoria($daoCategoria->getById($returnProduto->idCategoria));

        $daoFornecedor = new DaoFornecedor();

        $fornecedor = $daoFornecedor->getById($returnProduto->idFornecedor);

        $returnProduto->setFornecedor($fornecedor);

        return $returnProduto;
    }

    /**
     * Método de buscar todos
     */
    public function getAll()
    {

        $sql = new Sql();

        $returnProduto = $sql->select("SELECT * FROM tbProduto");

        $daoFornecedor = new DaoFornecedor();

        foreach ($returnProduto as $prod) {

            $prod->setFornecedor($daoFornecedor->getById($prod->id));

        }

        return $returnProduto;
    }

    public function select($rawQuery, $parameters = [])
    {
        $sql = new Sql();

        $prod = $sql->query($rawQuery, $parameters)->fetchAll(PDO::FETCH_CLASS, 'Produto');

        return $prod;
    }

    /**
     * Método para pegar os nomes dos produtos para montar o cabeçalho das tabelas
     */
    public function prodThTab($idPedido, $idFornecedor)
    {

        $idPedido = (int)$idPedido;
        $idFornecedor = (int)$idFornecedor;

        $sql = new Sql();

        $produtos = $sql->query("SELECT distinct prod.id, prod.descricao, prod.marca, prod.numero, prod.unidade FROM tbItemPedido iped
                                       JOIN tbProduto prod ON prod.id = iped.idProduto
                                       JOIN tbFornecedor forn ON prod.idFornecedor = forn.id
                                       JOIN tbEndereco ende ON forn.idEndereco = ende.id
                                       WHERE (iped.idPedido = :idPedido) AND (prod.idFornecedor = :idFornecedor) ORDER BY prod.descricao ASC",
            [":idPedido" => $idPedido, ":idFornecedor" => $idFornecedor]
        );


        return $produtos->fetchAll(PDO::FETCH_NUM);

    }

    /**
     * PEGA ITENS PARA MONTAR CORPO DA TABELA
     */
    public function pegaItens($idFornecedor, $idPedido, $idEscola, $idProduto)
    {

        $sql = new Sql();

        $prods = $sql->select("SELECT prod.id, prod.descricao, tip.quantidade, tip.idEscola FROM tbItemPedido tip
	JOIN tbProduto prod ON tip.idProduto = prod.id
	WHERE (tip.idEscola = :idEscola) AND (tip.idPedido = :idPedido) AND (prod.idFornecedor = :idFornecedor) AND (tip.idProduto = :idProduto) ORDER BY prod.descricao ASC",
            [

                ":idEscola" => $idEscola,
                ":idPedido" => $idPedido,
                ":idFornecedor" => $idFornecedor,
                ":idProduto" => $idProduto
            ]
        );

        return $prods;

    }

    /**
     * PEGA QUANTIDADE DOS ITENS PARA MONTAR CORPO DA TABELA DE RELATÓRIOS
     */
    public function pegaQuantidade($idFornecedor, $idPedido, $idEscola, $idProduto)
    {

        $sql = new Sql();

        $prods = $sql->select("SELECT tip.quantidade FROM tbItemPedido tip
	JOIN tbProduto prod ON tip.idProduto = prod.id
	WHERE (tip.idEscola = :idEscola) AND (tip.idPedido = :idPedido) AND (prod.idFornecedor = :idFornecedor) AND (tip.idProduto = :idProduto) ORDER BY prod.descricao ASC",
            [

                ":idEscola" => $idEscola,
                ":idPedido" => $idPedido,
                ":idFornecedor" => $idFornecedor,
                ":idProduto" => $idProduto
            ]
        );

        return $prods;

    }

    /**
     * Pega a quantidade total que cada produto foi pedido
     */
    public function contaTotalItem($idCategoria, $idPedido, $idProduto)
    {

        $sql = new Sql();

        $busca = $sql->select("
          SELECT sum(quantidade) total FROM tbItemPedido pid
        	JOIN tbProduto prod ON pid.idProduto = prod.id
        	WHERE (prod.idCategoria = :idCategoria) AND (pid.idPedido =  :idPedido) AND (prod.id = :idProduto);
        ", [
            ":idCategoria" => $idCategoria,
            ":idPedido" => $idPedido,
            ":idProduto" => $idProduto
        ]);

        return (int)$busca[0]->total;

    }

}