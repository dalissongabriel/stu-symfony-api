<?php


namespace App\Helper;


use Symfony\Component\HttpFoundation\Request;

class ExtratorDadosRequest
{
    private function buscaDadosRequest(Request $request)
    {
        $sortInfo = $request->query->get("sort");
        $filterInfo = $request->query->all();
        if (isset($filterInfo["sort"])) {
            unset($filterInfo["sort"]);
        }

        return [$sortInfo, $filterInfo];
    }
    public function buscaDadosOrdenacao(Request $request)
    {
        [$sortInfo, ] = $this->buscaDadosRequest($request);
        return $sortInfo;
    }

    public function buscaDadosFiltros(Request $request)
    {
        [, $filterInfo] = $this->buscaDadosRequest($request);
        return $filterInfo;
    }
}