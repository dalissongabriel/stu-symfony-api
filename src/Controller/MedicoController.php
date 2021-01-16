<?php


namespace App\Controller;


use App\Entity\Medico;
use App\Helper\MedicoFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicoController
{

    private EntityManagerInterface $entityManager;
    private MedicoFactory $medicoFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        MedicoFactory $medicoFactory
    )
    {

        $this->entityManager = $entityManager;
        $this->medicoFactory = $medicoFactory;
    }

    #[Route('/medicos', methods: ["POST"])]
    public function novo(Request $request): Response
    {
        $body = $request->getContent();
        $medico = $this->medicoFactory->criarMedico($body);

        $this->entityManager->persist($medico);
        $this->entityManager->flush();
        return new JsonResponse($medico);
    }

    #[Route('/medicos', methods: ["GET"])]
    public function buscarTodos(Request $request): Response
    {
        $repository = $this->entityManager->getRepository(Medico::class);
        $corpoRequisicao = $repository->findAll();
        return new JsonResponse($corpoRequisicao);
    }

    #[Route('/medicos/{id}', methods: ["GET"])]
    public function buscarUm(int $id, Request $request): Response
    {

        $medico = $this->buscaMedico($id);
        $codigo_status = is_null($medico) ? Response::HTTP_NO_CONTENT :200;

        return new JsonResponse($medico,$codigo_status);
    }

    #[Route('/medicos/{id}', methods: ["PUT"])]
    public function atualiza(int $id, Request $request): Response
    {

        $corpoRequisicao = $request->getContent();

        $medico = $this->medicoFactory->criarMedico($corpoRequisicao);

        $medicoAntigo = $this->buscaMedico($id);

        if (is_null($medicoAntigo)) {
            return new Response('',Response::HTTP_NOT_FOUND);
        }
        $medicoAntigo->nome = $medico->nome;
        $medicoAntigo->crm = $medico->crm;

        $this->entityManager->flush();

        return new JsonResponse($medicoAntigo);
    }

    #[Route("/medicos/{id}", methods: ["DELETE"])]
    public function remove(int $id): Response
    {

        $medico = $this->buscaMedico($id);
        if (is_null($medico)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $this->entityManager->remove($medico);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }


    /**
     * @param int $id
     * @return object|null
     */
    public function buscaMedico(int $id): ?object
    {

        $repository = $this
            ->entityManager
            ->getRepository(Medico::class);
        $medico = $repository->find($id);

        return $medico;
    }
}