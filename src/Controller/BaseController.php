<?php


namespace App\Controller;



use App\Helper\Exceptions\DatabaseException;
use App\Helper\ExtratorDadosRequest;
use App\Helper\Factorys\EntidadeFactoryInterface;
use App\Helper\Factorys\ResponseFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
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
    protected ExtratorDadosRequest $extratorDadosRequest;
    /**
     * @var CacheItemPoolInterface
     */
    protected CacheItemPoolInterface $cache;

    public function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager,
        EntidadeFactoryInterface $factory,
        ExtratorDadosRequest $extratorDadosRequest,
        CacheItemPoolInterface $cache
    )
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->extratorDadosRequest = $extratorDadosRequest;
        $this->cache = $cache;
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

        $responseFactory = new ResponseFactory(
            true,
            $entityList,
            Response::HTTP_OK,
            $page,
            $itemsPerPage
        );
        return $responseFactory->getResponse();
    }

    public function find(int $id): Response
    {
        $cacheKey = $this->cachePrefix() . $id;
        $entity =
            $this->cache->hasItem($cacheKey)
            ? $item = $this->cache->getItem($cacheKey)->get()
            : $this->repository->find($id);

        $statusCode = is_null($entity)
            ? Response::HTTP_NO_CONTENT
            : Response::HTTP_OK;

        $responseFactory = new ResponseFactory(
            true,
            $entity,
            $statusCode
        );

        return $responseFactory->getResponse();
    }

    public function remove(int $id): Response
    {
        $entity = $this->repository->find($id);

        if (is_null($entity)) {
            $responseFactory = $this->getResponseFactoryNotFound();
            return $responseFactory->getResponse();
        }

        try {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw new DatabaseException(
                "Não foi possível excluir o recurso pois ainda existem subrecursos vinculados a ele",
                Response::HTTP_PRECONDITION_REQUIRED
            );
        }



        $responseFactory = new ResponseFactory(
            true,
            $entity,
            Response::HTTP_NO_CONTENT
        );

        return $responseFactory->getResponse();

    }

    public function create(Request $request): Response
    {
        $content = $request->getContent();

        $entity = $this->factory->create($content);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $cacheItem = $this->cache->getItem(
            $this->cachePrefix() . $entity->getId(),
        );
        $cacheItem->set($entity);
        $this->cache->save($cacheItem);

        $responseFactory = new ResponseFactory(
            true,
            $entity,
            Response::HTTP_CREATED
        );

        return $responseFactory->getResponse();
    }

    public function update(int $id, Request $request): Response
    {
        $content = $request->getContent();
        $newEntity = $this->factory->create($content);

        $entity = $this->repository->find($id);

        if (is_null($entity)) {
            $responseFactory = $this->getResponseFactoryNotFound();
            return $responseFactory->getResponse();
        }

        $this->atualizaEntidade($entity, $newEntity);
        $this->entityManager->flush();

        $responseFactory = new ResponseFactory(
            true,
            $entity,
            Response::HTTP_OK
        );

        return $responseFactory->getResponse();
    }

    abstract public function atualizaEntidade(object $entidade, object $novaEntidade);
    abstract public function cachePrefix(): string;

    /**
     * @return ResponseFactory
     */
    public function getResponseFactoryNotFound(): ResponseFactory
    {
        $responseFactory = new ResponseFactory(
            false,
            "Entidade não foi encontrada",
            Response::HTTP_NOT_FOUND
        );
        return $responseFactory;
    }

}