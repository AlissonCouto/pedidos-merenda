<?php
/**
 * Created by PhpStorm.
 * User: alissoncouto
 * Date: 04/09/2019
 * Time: 18:33
 */

class Modalidade
{

    private $id;
    private $modalidade;

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

}