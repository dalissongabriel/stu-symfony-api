<?php


namespace App\Helper\Factorys;


use App\Entity\Especialidade;
use App\Helper\Exceptions\EntityFactoryException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class EspecialidadeFactory implements EntidadeFactoryInterface
{

    public function create(string $requestContent): Especialidade
    {
        $content = json_decode($requestContent);

        if (is_null($content)) {
            throw new BadRequestException(
                "Requisição mal feita: confira o corpo da requisição",
                Response::HTTP_BAD_REQUEST
            );
        }

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