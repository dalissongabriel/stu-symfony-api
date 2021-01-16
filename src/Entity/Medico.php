<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Medico
 * @ORM\Entity()
 */
class Medico
{
    public string $nome;
    public int $crm;

    /**
     * Medico constructor.
     * @param string $nome
     * @param int $crm
     */
    public function __construct(string $nome, int $crm)
    {
        $this->nome = $nome;
        $this->crm = $crm;
    }



}