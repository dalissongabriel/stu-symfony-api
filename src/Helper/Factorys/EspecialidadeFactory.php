<?php


namespace App\Helper\Factorys;


use App\Entity\Especialidade;
use App\Helper\Exceptions\EntityFactoryException;

class EspecialidadeFactory implements EntidadeFactoryInterface
{

    public function create(string $requestContent): Especialidade
    {
        $content = json_decode($requestContent);
        $this->checkRequiredProperties($content);

        $especialidade = new Especialidade();
        $especialidade->setDescricao($content->descricao);

        return $especialidade;
    }

    private function checkRequiredProperties(object $content)
    {
        if(!property_exists($content,"descricao")) {
            throw new EntityFactoryException(
                "Especialidade precisa de descrição"
            );
        }
    }

}