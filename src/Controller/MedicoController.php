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

    public function __construct(
        EntityManagerInterface $entityManager,
        MedicoFactory $factory,
        MedicoRepository $repository
    )
    {
        parent::__construct($repository, $entityManager, $factory);
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