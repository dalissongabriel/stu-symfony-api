<?php

namespace App\Controller;

use App\Entity\User;
use App\Helper\ResponseFactory;
use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends AbstractController
{
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
    public function index(Request $request): Response
    {
        $content = json_decode($request->getContent());

        if (is_null($content->username) || is_null($content->password)) {
            $responseFactory = new ResponseFactory(
                false,
                "Favor enviar usuário e senha",
                Response::HTTP_BAD_REQUEST
            );
            return $responseFactory->getResponse();
        }

        $user = $this->repository->findOneBy(["username" => $content->username]);

        $passwordValid = $this->encoder->isPasswordValid($user, $content->password);

        if (!$passwordValid) {
            $responseFactory = new ResponseFactory(
                false,
                "Usuário ou senha inválidos",
                Response::HTTP_UNAUTHORIZED
            );

            return $responseFactory->getResponse();
        }

        $token = JWT::encode(
            ["username" => $user->getUsername()],
            '$argon2i$v=19$m=65536,t=4,p=1$dzFUTVpyMk5ENk5RcDRkMA$oDnxVbg/A6heA6rOxncfp/zktWinhB/1LmSWCkJYXOg');

        $responseFactory = new ResponseFactory(
            true,
            ["access_token"=>$token]
        );

        return $responseFactory->getResponse();

    }
}
