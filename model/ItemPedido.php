<?php
/**
 * Created by PhpStorm.
 * User: alissoncouto
 * Date: 21/08/2019
 * Time: 12:28
 */

class ItemPedido
{

    private $id;
    private $produto;
    private $quantidade;
    private $fornecedor;
    private $escola;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return Produto
     */
    public function getProduto()
    {
        return $this->produto;
    }

    /**
     * @param Produto $produto
     */
    public function setProduto(Produto $produto): void
    {
        $this->produto = $produto;
    }

    /**
     * @return mixed
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * @param mixed $quantidade
     */
    public function setQuantidade($quantidade): void
    {
        $this->quantidade = $quantidade;
    }

    /**
     * @return Fornecedor
     */
    public function getFornecedor(): Fornecedor
    {
        return $this->fornecedor;
    }

    /**
     * @param Fornecedor $fornecedor
     */
    public function setFornecedor(Fornecedor $fornecedor): void
    {
        $this->fornecedor = $fornecedor;
    }

    /**
     * @param Escola $escola
     */
    public function getEscola(): Escola
    {
        return $this->escola;
    }

}