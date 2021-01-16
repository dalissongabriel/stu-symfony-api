<?php


namespace App\Controller;



use App\Helper\EntidadeFactoryInterface;
use App\Helper\ExtratorDadosRequest;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


abstract class BaseController extends AbstractController
{
    /**
     * @var ObjectRepository
     */
    protected ObjectRepository $repository;
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;
    /**
     * @var EntidadeFactoryInterface
     */
    protected EntidadeFactoryInterface $factory;
    /**
     * @var ExtratorDadosRequest
     */
    private ExtratorDadosRequest $extratorDadosRequest;

    public function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager,
        EntidadeFactoryInterface $factory,
        ExtratorDadosRequest $extratorDadosRequest
    )
    {

        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->extratorDadosRequest = $extratorDadosRequest;
    }

    public function findAll(Request $request): Response
    {
        $sortInfo = $this->extratorDadosRequest->buscaDadosOrdenacao($request);
        $filterInfo = $this->extratorDadosRequest->buscaDadosFiltros($request);
        [$page, $itemsPerPage] = $this->extratorDadosRequest->buscarDadosPaginacao($request);

        $entityList = $this->repository->findBy(
            $filterInfo,
            $sortInfo,
            $itemsPerPage,
            ($page - 1) * $itemsPerPage
        );
        return new JsonResponse($entityList);
    }

    public function find(int $id): Response
    {
        $entity = $this->repository->find($id);

        is_null($entity)
            ? $statusCode = Response::HTTP_NO_CONTENT
            : $statusCode = 200;

        return new JsonResponse($entity, $statusCode);
    }

    public function remove(int $id): Response
    {
        $entity = $this->repository->find($id);

        if (is_null($entity)) {
            return new Response('',Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function create(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $entitdade = $this->factory->criar($corpoRequisicao);

        $this->entityManager->persist($entitdade);
        $this->entityManager->flush();

        return new JsonResponse($entitdade, Response::HTTP_CREATED);
    }

    public function update(int $id, Request $request): Response
    {
        $dadosRequest = $request->getContent();
        $novaEntidade = $this->factory->criar($dadosRequest);
        $entidade = $this->repository->find($id);

        if (is_null($entidade)) {
            return new Response('',Response::HTTP_NOT_FOUND);
        }
        $this->atualizaEntidade($entidade, $novaEntidade);

        $this->entityManager->flush();

        return new JsonResponse($entidade);
    }

    abstract public function atualizaEntidade(object $entidade, object $novaEntidade);

}