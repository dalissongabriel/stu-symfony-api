<?php

namespace App\Controller;

use App\Helper\Factorys\LoginFactory;
use App\Helper\Factorys\ResponseFactory;
use App\Helper\KeyAuthenticationJWTTrait;
use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends AbstractController
{
    use KeyAuthenticationJWTTrait;
    /**
     * @var UserRepository
     */
    private UserRepository $repository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    /**
     * LoginController constructor.
     * @param UserRepository $repository
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(
        UserRepository $repository,
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->repository = $repository;
        $this->encoder = $encoder;
    }

    #[Route('/login', name: 'login', methods: ["POST"])]
    public function login(Request $request): Response
    {
        $LoginFactory = new LoginFactory(
            $this->repository,
            $this->encoder
        );

        /**
         * @var User $user
         */
        $user = $LoginFactory->create($request->getContent());

        $token = JWT::encode(
            ["username" => $user->getUsername()],
            KeyAuthenticationJWTTrait::getKey()
        );

        $responseFactory = new ResponseFactory(
            true,
            ["access_token"=>$token]
        );

        return $responseFactory->getResponse();

    }
}
