<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\EspecialidadeFactory;
use App\Helper\ExtratorDadosRequest;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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


    #[Route("/especialidades/{id}", methods: ["DELETE"])]
    public function remove(int $id): Response
    {
        $especialidade = $this->repository->find($id);

        if (is_null($especialidade)) {
            return new Response('',Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($especialidade);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
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
