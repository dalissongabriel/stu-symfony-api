<?php


namespace App\Helper\Factorys;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    private bool $success;
    private ?int $currentPage;
    private ?int $itemsPerPage;
    private $data;
    private int $statusCode;

    public function __construct(
        bool $success,
        $data,
        int $statusCode = Response::HTTP_OK,
        ?int $currentPage = null,
        ?int $itemsPerPage = null
    )
    {
        $this->success = $success;
        $this->data = $data;
        $this->currentPage = $currentPage;
        $this->itemsPerPage = $itemsPerPage;
        $this->statusCode = $statusCode;
    }

    public static function fromError(\Throwable $error, int $statusCode = HTTP_INTERNAL_SERVER_ERROR): self
    {
        return new self(
            false,
            ['message' => $error->getMessage()],
            $statusCode);
    }

    public function getResponse(): JsonResponse
    {
        $responseContent = [
            "success"=>$this->success,
            "currentPage"=>$this->currentPage,
            "itemsPerPage"=>$this->itemsPerPage,
            "data"=>$this->data
        ];

        if (is_null($this->currentPage)) {
            unset($responseContent["currentPage"]);
            unset($responseContent["itemsPerPage"]);
        }

        if (is_null($this->data)) {
            unset($responseContent["data"]);
        }

        return new JsonResponse($responseContent, $this->statusCode);
    }
}