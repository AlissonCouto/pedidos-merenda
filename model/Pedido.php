<?php
/**
 * Created by PhpStorm.
 * User: alissoncouto
 * Date: 21/08/2019
 * Time: 12:23
 */

class Pedido{

    private $data;
    private $numero;
    private $idModalidade;
    private $itens;

    /**
     * Pedido constructor.
     * @param $data
     */
    public function __construct()
    {
        $this->data = date('y/m/d');
    }

    /**
     * @return mixed
     */
    public function getIdModalidade()
    {
        return $this->idModalidade;
    }

    /**
     * @param mixed $idModalidade
     */
    public function setIdModalidade($idModalidade): void
    {
        $this->idModalidade = $idModalidade;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return ItemPedido
     */
    public function getItens()
    {
        return $this->itens;
    }

    /**
     * @param ItemPedido $itens
     */
    public function setItens($itens): void
    {
        $this->itens = $itens;
    }

    public function addItem(ItemPedido $item){

        $this->itens[] = $item;

    }
}