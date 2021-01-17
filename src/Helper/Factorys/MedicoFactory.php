<?php


namespace App\Helper\Factorys;


use App\Entity\Medico;
use App\Helper\Exceptions\EntityFactoryException;
use App\Helper\Exceptions\EntityNotFoundException;
use App\Repository\EspecialidadeRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class MedicoFactory implements EntidadeFactoryInterface
{
    /**
     * @var EspecialidadeRepository
     */
    private EspecialidadeRepository $especialidadeRepository;

    public function __construct(EspecialidadeRepository $especialidadeRepository)
    {
        $this->especialidadeRepository = $especialidadeRepository;
    }

    public function create(string $requestContent): Medico
    {
        $content = json_decode($requestContent);

        if (is_null($content)) {
            throw new BadRequestException(
                "Requisição mal feita: confira o corpo da requisição",
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->checkRequiredProperties($content);

        $especialidadeId = $content->especialidadeId;
        $especialidade = $this->especialidadeRepository->find($especialidadeId);

        if (is_null($especialidade)) {
            throw new EntityNotFoundException(
                "Especialidade: $especialidadeId não existe. Informe uma especialidade cadastrada",
                Response::HTTP_BAD_REQUEST
            );
        }

        $medico = new Medico();
        $medico->setCrm($content->crm)
            ->setNome($content->nome)
            ->setEspecialidade($especialidade);

        return $medico;
    }

    private function checkRequiredProperties(object $content)
    {
        if(!property_exists($content,"nome")) {
            throw new EntityFactoryException(
                "Para a entidade Médico deve ser informado o campo: nome",
                Response::HTTP_BAD_REQUEST

            );
        }

        if(!property_exists($content,"crm")) {
            throw new EntityFactoryException(
                "Para a entidade Médico deve ser informado o campo: crm",
                Response::HTTP_BAD_REQUEST
            );
        }

        if(!property_exists($content,"especialidadeId")) {
            throw new EntityFactoryException(
                "Para a entidade Médico deve ser informado o campo: especialidadeId",
                Response::HTTP_BAD_REQUEST
            );
        }

    }
}