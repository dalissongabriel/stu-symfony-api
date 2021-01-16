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

    #[Route("/especialidades/{id}", methods: ["GET"])]
    public function buscarUma(int $id): Response
    {
        $especialidade = $this->repository->find($id);

        is_null($especialidade)
            ? $statusCode = Response::HTTP_NO_CONTENT
            : $statusCode = 200;

        return new JsonResponse($especialidade, $statusCode);
    }
}
