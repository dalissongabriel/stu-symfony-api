<?php


namespace App\Helper\Factorys;


use App\Helper\Exceptions\EntityFactoryException;
use App\Helper\Exceptions\EntityNotFoundException;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginFactory implements EntidadeFactoryInterface
{
    /**
     * @var UserRepository
     */
    private UserRepository $repository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    public function __construct(
        UserRepository $repository,
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->repository = $repository;
        $this->encoder = $encoder;
    }

    public function create(string $requestContent)
    {
        $content = json_decode($requestContent);
        if (is_null($content)) {
            throw new BadRequestException(
                "Requisição mal feita: confira o corpo da requisição",
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->checkRequiredProperties($content);

        $user = $this->repository->findOneBy(["username" => $content->username]);

        if (is_null($user)) {
            throw new EntityNotFoundException(
                "Usuário ou senha inválidos",
                Response::HTTP_UNAUTHORIZED
            );
        }

        $passwordValid = $this->encoder->isPasswordValid($user, $content->password);

        if (!$passwordValid) {
            throw new EntityNotFoundException(
                "Usuário ou senha inválidos",
                Response::HTTP_UNAUTHORIZED
            );
        }

        return $user;
    }

    private function checkRequiredProperties(object $content)
    {
        if(!property_exists($content,"username")) {
            throw new EntityFactoryException(
                "Para logar, deve ser informado o campo: username",
                Response::HTTP_BAD_REQUEST
            );
        }

        if(!property_exists($content,"password")) {
            throw new EntityFactoryException(
                "Para logar, deve ser informado o campo: password",
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}