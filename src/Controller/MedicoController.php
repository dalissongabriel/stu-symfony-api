<?php


namespace App\Controller;

use App\Entity\Medico;
use App\Helper\ExtratorDadosRequest;
use App\Helper\Factorys\MedicoFactory;
use App\Helper\Factorys\ResponseFactory;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicoController extends BaseController
{

    public function __construct(
        EntityManagerInterface $entityManager,
        MedicoFactory $factory,
        MedicoRepository $repository,
        ExtratorDadosRequest $extratorDadosRequest
    )
    {
        parent::__construct($repository, $entityManager, $factory, $extratorDadosRequest);
    }

    #[Route("/especialidades/{id}/medicos", methods: ["GET"])]
    public function buscarPorEspecialidade(int $id, Request $request): Response
    {
        $sortInfo = $this->extratorDadosRequest->buscaDadosOrdenacao($request);
        $filterInfoQueryString = $this->extratorDadosRequest->buscaDadosFiltros($request);
        $filterInfo = array_merge($filterInfoQueryString, ["especialidade"=>$id]);
        [$page, $itemsPerPage] = $this->extratorDadosRequest->buscarDadosPaginacao($request);

        $medicoList = $this->repository->findBy(
            $filterInfo,
            $sortInfo,
            $itemsPerPage,
            ($page - 1) * $itemsPerPage
        );

        $responseFactory = new ResponseFactory(
            true,
            $medicoList,
        Response::HTTP_OK,
            $page,
            $itemsPerPage
        );

        return $responseFactory->getResponse();
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