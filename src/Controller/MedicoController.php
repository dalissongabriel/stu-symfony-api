<?php


namespace App\Controller;


use App\Entity\Medico;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicoController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }

    #[Route('/medicos', methods: ["POST"])]
    public function novo(Request $request): Response
    {
        $body = $request->getContent();
        $dados = json_decode($body);
        $medico = new Medico();

        $medico->nome = $dados->nome;
        $medico->crm = $dados->crm;

        $this->entityManager->persist($medico);
        $this->entityManager->flush();
        return new JsonResponse($medico);
    }

    #[Route('/medicos', methods: ["GET"])]
    public function buscarTodos(Request $request): Response
    {
        $repository = $this->entityManager->getRepository(Medico::class);
        $data = $repository->findAll();
        return new JsonResponse($data);
    }

    #[Route('/medicos/{id}', methods: ["GET"])]
    public function buscarUm(int $id, Request $request): Response
    {
        $repository = $this->entityManager->getRepository(Medico::class);
        $medico = $repository->findOneBy(["id"=>$id]);

        $codigo_status = is_null($medico) ? Response::HTTP_NO_CONTENT :200;

        return new JsonResponse($medico,$codigo_status);
    }

    #[Route('/medicos/{id}', methods: ["PUT"])]
    public function atualiza(int $id, Request $request): Response
    {
        $data = $request->getContent();
        $jsonData = json_decode($data);

        $medico = new Medico();
        $medico->nome = $jsonData->nome;
        $medico->crm = $jsonData->crm;

        $repositorio = $this->entityManager->getRepository(Medico::class);

        $medicoAntigo = $repositorio->find($id);
        if (is_null($medicoAntigo)) {
            return new Response('',Response::HTTP_NOT_FOUND);
        }
        $medicoAntigo->nome = $medico->nome;
        $medicoAntigo->crm = $medico->crm;

        $this->entityManager->flush();

        return new JsonResponse($medicoAntigo);
    }
}