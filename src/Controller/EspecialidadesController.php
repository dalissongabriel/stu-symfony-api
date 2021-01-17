<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\ExtratorDadosRequest;
use App\Helper\Factorys\EspecialidadeFactory;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;


class EspecialidadesController extends BaseController
{

    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeRepository $repository,
        EspecialidadeFactory $factory,
        ExtratorDadosRequest $extratorDadosRequest,
        CacheItemPoolInterface $cache
    )
    {
        parent::__construct($repository, $entityManager, $factory,$extratorDadosRequest,$cache);
    }

    /**
     * @param Especialidade $entidade
     * @param Especialidade $novaEntidade
     */
    public function atualizaEntidade(object $entidade, object $novaEntidade)
    {
        $entidade->setDescricao($novaEntidade->getDescricao());
    }

    public function cachePrefix(): string
    {
        return "especialidade_";
    }
}
