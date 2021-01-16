<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MedicoRepository;
/**
 * @ORM\Entity(repositoryClass="MedicoRepository:class")
*/
class Medico implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;
    /**
     * @ORM\Column(type="string")
     */
    private string $nome;
    /**
     * @ORM\Column(type="integer")
     */
    private int $crm;

    /**
     * @ORM\ManyToOne(targetEntity=Especialidade::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $especialidade;


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * @return int
     */
    public function getCrm(): int
    {
        return $this->crm;
    }

    /**
     * @param int $crm
     */
    public function setCrm(int $crm): self
    {
        $this->crm = $crm;

        return $this;
    }

    public function getEspecialidade(): ?Especialidade
    {
        return $this->especialidade;
    }

    public function setEspecialidade(?Especialidade $especialidade): self
    {
        $this->especialidade = $especialidade;

        return $this;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}