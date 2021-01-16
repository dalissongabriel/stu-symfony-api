<?php


namespace App\Controller;



use App\Helper\EntidadeFactoryInterface;
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

    public function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager,
        EntidadeFactoryInterface $factory
    )
    {

        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
    }

    public function findAll(): Response
    {

        $entityList = $this->repository->findAll();
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


}