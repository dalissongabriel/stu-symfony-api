<?php


namespace App\Helper\Factorys;

interface EntidadeFactoryInterface
{
    public function create(string $requestContent);
}