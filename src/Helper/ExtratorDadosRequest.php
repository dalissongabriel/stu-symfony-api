<?php


namespace App\Helper;


use Symfony\Component\HttpFoundation\Request;

class ExtratorDadosRequest
{
    private function buscaDadosRequest(Request $request)
    {
        $queryString = $request->query->all();

        $sortInfo = null;
        if (array_key_exists("sort", $queryString)) {
            $sortInfo =  $queryString["sort"];
            unset($queryString['sort']);
        }

        $paginaAtual = 1;
        if (array_key_exists("page",$queryString)) {
            $paginaAtual = $queryString['page'];
            unset($queryString["page"]);
        }

        $filterInfo = [];
        if (array_key_exists("filter", $queryString)) {
            $filterInfo = $queryString["filter"];
            unset($queryString["filter"]);
        }

        $itemsPerPage = 5;
        if (array_key_exists("itemsPerPage", $queryString)) {
            $itemsPerPage = $queryString["itemsPerPage"];
            unset($queryString["itemsPerPage"]);
        }

        return [$sortInfo, $filterInfo, $paginaAtual, $itemsPerPage];
    }
    public function buscaDadosOrdenacao(Request $request)
    {
        [$sortInfo,,, ] = $this->buscaDadosRequest($request);
        return $sortInfo;
    }

    public function buscaDadosFiltros(Request $request)
    {
        [, $filterInfo,,] = $this->buscaDadosRequest($request);
        return $filterInfo;
    }

    public function buscarDadosPaginacao(Request $request)
    {
        [,, $paginaAtual, $itemsPerPage] = $this->buscaDadosRequest($request);
        return [$paginaAtual, $itemsPerPage];
    }
}