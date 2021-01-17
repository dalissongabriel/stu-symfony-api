<?php


namespace App\Security;


use App\Helper\Factorys\ResponseFactory;
use App\Helper\KeyAuthenticationJWTTrait;
use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JWTAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var UserRepository
     */
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
    }

    public function supports(Request $request)
    {
        return $request->getPathInfo() !== "/login";
    }

    public function getCredentials(Request $request)
    {
        $token = $request->headers->get("Authorization");
        $token = str_replace('Bearer','', $token);
        try {
            return JWT::decode(
                $token,
                KeyAuthenticationJWTTrait::getKey(),
                ["HS256"]
            );
        } catch (\Exception $ex) {
            return false;
        }

    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!is_object($credentials) || !property_exists($credentials, "username")) {
            return null;
        }
        $username = $credentials->username;
        return $this->repository->findOneBy(["username"=>$username]);

    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return is_object($credentials) && property_exists($credentials, "username");
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $responseFactory = new ResponseFactory(
            false,
            "Falha na autentificação",
            Response::HTTP_UNAUTHORIZED
        );
        return $responseFactory->getResponse();
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return null;
    }

    public function supportsRememberMe()
    {
        return false;
    }
}