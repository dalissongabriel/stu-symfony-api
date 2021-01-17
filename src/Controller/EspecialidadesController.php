<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\ExtratorDadosRequest;
use App\Helper\Factorys\EspecialidadeFactory;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends BaseController
{

    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeRepository $repository,
        EspecialidadeFactory $factory,
        ExtratorDadosRequest $extratorDadosRequest
    )
    {
        parent::__construct($repository, $entityManager, $factory,$extratorDadosRequest);

    }

    /**
     * @param Especialidade $entidade
     * @param Especialidade $novaEntidade
     */
    public function atualizaEntidade(object $entidade, object $novaEntidade)
    {
        $entidade->setDescricao($novaEntidade->getDescricao());
    }
}
