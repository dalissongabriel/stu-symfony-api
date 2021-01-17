<?php


namespace App\Helper\Factorys;


use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityNotFoundException;
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
        $this->checkRequiredProperties($content);

        $especialidadeId = $content->especialidadeId;
        $especialidade = $this->especialidadeRepository->find($especialidadeId);

        if (is_null($especialidade)) {
            throw new EntityNotFoundException(
                "Especialidade: $especialidadeId não existe. Informe uma especialidade cadastrada"
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
                "Para criar um médico, deve ser informado o campo: nome",
                Response::HTTP_BAD_REQUEST

            );
        }

        if(!property_exists($content,"crm")) {
            throw new EntityFactoryException(
                "Para criar um médico, deve ser informado o campo: crm",
                Response::HTTP_BAD_REQUEST
            );
        }

        if(!property_exists($content,"especialidadeId")) {
            throw new EntityFactoryException(
                "Para criar um médico, deve ser informado o campo: especialidadeId",
                Response::HTTP_BAD_REQUEST
            );
        }

    }
}