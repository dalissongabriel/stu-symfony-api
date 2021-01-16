<?php

namespace App\Controller;

use App\Helper\EspecialidadeFactory;
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
        EspecialidadeFactory $factory
    )
    {
        parent::__construct($repository, $entityManager, $factory);

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
