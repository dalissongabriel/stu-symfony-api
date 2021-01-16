<?php


namespace App\Controller;



use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    public function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager)
    {

        $this->repository = $repository;
        $this->entityManager = $entityManager;
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

}