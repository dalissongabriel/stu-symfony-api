<?php


namespace App\Controller;



use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


abstract class BaseController extends AbstractController
{
    /**
     * @var ObjectRepository
     */
    private ObjectRepository $repository;

    public function __construct(ObjectRepository $repository)
    {

        $this->repository = $repository;
    }

    public function findAll(): Response
    {

        $entityList = $this->repository->findAll();
        return new JsonResponse($entityList);
    }

}