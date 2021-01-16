<?php


namespace App\Helper;


use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;

class MedicoFactory
{
    /**
     * @var EspecialidadeRepository
     */
    private EspecialidadeRepository $especialidadeRepository;

    public function __construct(EspecialidadeRepository $especialidadeRepository)
    {
        $this->especialidadeRepository = $especialidadeRepository;
    }

    public function criarMedico(string $json): Medico
    {
        $dadosJson = json_decode($json);
        $especialidadeId = $dadosJson->especialidadeId;
        $especialidade = $this->especialidadeRepository->find($especialidadeId);


        $medico = new Medico();
        $medico->setCrm( $dadosJson->crm)
            ->setNome($dadosJson->nome)
            ->setEspecialidade($especialidade);

        return $medico;
    }

}