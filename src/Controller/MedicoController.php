<?php


namespace App\Controller;

use App\Helper\MedicoFactory;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicoController extends BaseController
{

    private EntityManagerInterface $entityManager;
    private MedicoFactory $medicoFactory;
    private MedicoRepository $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        MedicoFactory $medicoFactory,
        MedicoRepository $repository
    )
    {
        parent::__construct($repository);
        $this->entityManager = $entityManager;
        $this->medicoFactory = $medicoFactory;
        $this->repository = $repository;
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

        $dadosNovos = json_decode($corpoRequisicao);

        $medico = $this->buscaMedico($id);

        if (is_null($medico)) {
            return new Response('',Response::HTTP_NOT_FOUND);
        }
        $medico->setNome($dadosNovos->nome);
        $medico->setCrm($medico->crm);

        $this->entityManager->flush();

        return new JsonResponse($medico);
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

    #[Route("/especialidades/{id}/medicos", methods: ["GET"])]
    public function buscarPorEspecialidade(int $id): Response
    {
        $medicoList = $this->repository->findBy(["especialidade"=>$id]);

        return new JsonResponse($medicoList);
    }


    /**
     * @param int $id
     * @return object|null
     */
    public function buscaMedico(int $id): ?object
    {
        $medico = $this->repository->find($id);

        return $medico;
    }
}