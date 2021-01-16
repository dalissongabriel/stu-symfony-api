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
        $medico = new Medico($dados->nome , $dados->crm);
        $this->entityManager->persist($medico);
        $this->entityManager->flush();
        return new JsonResponse($medico);
    }
}