<?php


namespace App\Helper;


use App\Entity\Especialidade;

class EspecialidadeFactory implements EntidadeFactoryInterface
{

    public function criar(string $corpoRequisicao): Especialidade
    {

        $dadosJson = json_decode($corpoRequisicao);
        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadosJson->descricao);

        return $especialidade;
    }
}