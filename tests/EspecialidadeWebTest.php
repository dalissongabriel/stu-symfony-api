<?php


namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EspecialidadeWebTest extends WebTestCase
{
    public function testShouldEnsureAccessWillNotBeAuthorizedWithoutAuthentication()
    {
        $client = static::createClient();
        $client->request('GET', '/especialidades');
        $response = $client->getResponse();

        self::assertEquals(401, $response->getStatusCode());
    }
    public function testShouldEnsureListWillBeReturnedOnTheRouteFindAll()
    {
        $client = static::createClient();
        $token = $this->login($client);
        $client->request('GET', '/especialidades',[],[],[
            'HTTP_AUTHORIZATION'=>$token
        ]);
        $response = $client->getResponse();

        $content = json_decode($response->getContent());

        self::assertTrue($content->success);
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testShouldEnsureThatTheEntityWasSuccessful()
    {
        $client = static::createClient();
        $token = $this->login($client);


        $client->request('POST', '/especialidades',[],[],[
            'HTTP_AUTHORIZATION'=>$token,
            'CONTENT_TYPE'=>'application/json'
        ], json_encode([
                "descricao"=>"teste"
            ]));


        $response = $client->getResponse();

        $content = json_decode($response->getContent());

        self::assertTrue($content->success);
        self::assertEquals(201, $response->getStatusCode());
    }

    private function login(KernelBrowser $client): string
    {
        $client->request('POST','/login',[],[],[
            'CONTENT_TYPE'=>'application/json'],
            json_encode([
                "username"=>"usuario",
                "password"=>"4321"
            ]));
        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        return $content->data->access_token;
    }
}