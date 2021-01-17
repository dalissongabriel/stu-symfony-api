<?php


namespace App\Helper;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    private bool $success;
    private ?int $currentPage;
    private ?int $itemsPerPage;
    private $responseContent;
    private int $statusCode;

    public function __construct(
        bool $success,
        $responseContent,
        int $statusCode = Response::HTTP_OK,
        ?int $currentPage = null,
        ?int $itemsPerPage = null
    )
    {
        $this->success = $success;
        $this->responseContent = $responseContent;
        $this->currentPage = $currentPage;
        $this->itemsPerPage = $itemsPerPage;
        $this->statusCode = $statusCode;
    }

    public function getResponse(): JsonResponse
    {
        $responseContent = [
            "success"=>$this->success,
            "currentPage"=>$this->currentPage,
            "itemsPerPage"=>$this->itemsPerPage,
            "data"=>$this->responseContent
        ];

        if (is_null($this->currentPage)) {
            unset($responseContent["currentPage"]);
            unset($responseContent["itemsPerPage"]);
        }

        if (is_null($this->responseContent)) {
            unset($responseContent["data"]);
        }

        return new JsonResponse($responseContent, $this->statusCode);
    }
}