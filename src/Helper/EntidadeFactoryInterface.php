<?php


namespace App\Helper;

interface EntidadeFactoryInterface
{
    public function create(string $requestContent);
}