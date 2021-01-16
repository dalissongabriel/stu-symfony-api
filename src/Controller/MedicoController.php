<?php


namespace App\Controller;

use App\Entity\Medico;
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

    #[Route("/especialidades/{id}/medicos", methods: ["GET"])]
    public function buscarPorEspecialidade(int $id): Response
    {
        $medicoList = $this->repository->findBy(["especialidade"=>$id]);

        return new JsonResponse($medicoList);
    }

    /**
     * @param Medico $entidade
     * @param Medico $novaEntidade
     */
    public function atualizaEntidade(object $entidade, object $novaEntidade)
    {
        $entidade->setNome($novaEntidade->getNome())
            ->setCrm($novaEntidade->getCrm())
            ->setEspecialidade($novaEntidade->getEspecialidade());
    }
}