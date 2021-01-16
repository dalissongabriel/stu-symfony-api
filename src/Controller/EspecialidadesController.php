<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends BaseController
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
        parent::__construct($repository);
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

    #[Route("/especialidades/{id}", methods: ["GET"])]
    public function buscarUma(int $id): Response
    {
        $especialidade = $this->repository->find($id);

        is_null($especialidade)
            ? $statusCode = Response::HTTP_NO_CONTENT
            : $statusCode = 200;

        return new JsonResponse($especialidade, $statusCode);
    }

    #[Route("/especialidades/{id}",methods: ["PUT"])]
    public function atualiza(int $id, Request $request): Response
    {
        $dadosRequest = $request->getContent();
        $dadosJson = json_decode($dadosRequest);

        $especialidade = $this->repository->find($id);

        if (is_null($especialidade)) {
            return new Response('',Response::HTTP_NOT_FOUND);
        }

        $especialidade->setDescricao($dadosJson->descricao);

        $this->entityManager->flush();

        return new JsonResponse($especialidade);
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
}
