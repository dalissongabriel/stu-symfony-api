<?php


namespace App\Helper;


use App\Entity\Medico;

class MedicoFactory
{
    public function criarMedico(string $json): Medico
    {
        $dadosJson = json_decode($json);

        $medico = new Medico();

        $medico->crm = $dadosJson->crm;
        $medico->nome = $dadosJson->nome;

        return $medico;
    }

}