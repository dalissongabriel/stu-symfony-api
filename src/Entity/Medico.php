<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * @ORM\Entity()
*/
class Medico
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public int $id;
    /**
     * @ORM\Column(type="string")
     */
    public string $nome;
    /**
     * @ORM\Column(type="integer")
     */
    public int $crm;
}