<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var EspecialidadeRepository
     */
    private EspecialidadeRepository $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeRepository $repository
    )
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    #[Route('/especialidades', methods: ["POST"])]
    public function nova(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $dadosEmJson = json_decode($corpoRequisicao);
        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadosEmJson->descricao);

        $this->entityManager->persist($especialidade);
        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }

    #[Route("/especialidades", methods: ["GET"])]
    public function buscarTodos(): Response
    {
        $especialidadeList = $this->repository->findAll();

        return new JsonResponse($especialidadeList);
    }
}
