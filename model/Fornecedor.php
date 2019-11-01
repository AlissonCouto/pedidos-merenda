<?php
/**
 * Created by PhpStorm.
 * User: alissoncouto
 * Date: 21/08/2019
 * Time: 12:47
 */

class Fornecedor
{

    private $id;
    private $razaosocial;
    private $cnpj;
    private $endereco;

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
     * @return mixed
     */
    public function getRazaoSocial()
    {
        return $this->razaosocial;
    }

    /**
     * @param mixed $razaosocial
     */
    public function setRazaoSocial($razaosocial): void
    {
        $this->razaosocial = $razaosocial;
    }

    /**
     * @return mixed
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * @param mixed $cnpj
     */
    public function setCnpj($cnpj): void
    {
        $this->cnpj = $cnpj;
    }

    /**
     * @return Endereco
     */
    public function getEndereco(): Endereco
    {
        return $this->endereco;
    }

    /**
     * @param Endereco $endereco
     */
    public function setEndereco($endereco): void
    {
        $this->endereco = $endereco;
    }

}