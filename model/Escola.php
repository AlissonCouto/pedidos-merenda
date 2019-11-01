<?php
/**
 * Created by PhpStorm.
 * User: alissoncouto
 * Date: 21/08/2019
 * Time: 12:33
 */

class Escola
{

    private $id;
    private $nome;
    private $modalidade;
    private $telefone;
    private $endereco;
    private $alunos;

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
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome): void
    {
        $this->nome = $nome;
    }

    /**
     * @return mixed
     */
    public function getModalidade()
    {
        return $this->modalidade;
    }

    /**
     * @param mixed $modalidade
     */
    public function setModalidade($modalidade): void
    {
        $this->modalidade = $modalidade;
    }

    /**
     * @return mixed
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * @param mixed $telefone
     */
    public function setTelefone($telefone): void
    {
        $this->telefone = $telefone;
    }

    /**
     * @return Endereco
     */
    public function getEndereco()
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

    /**
     * @return mixed
     */
    public function getAlunos()
    {
        return $this->alunos;
    }

    /**
     * @param mixed $alunos
     */
    public function setAlunos($alunos): void
    {
        $this->alunos = $alunos;
    }

}