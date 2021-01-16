<?php


namespace App\Helper;


use Doctrine\ORM\Mapping\Entity;

interface EntidadeFactoryInterface
{
    public function criar(string $corpoRequisicao);
}