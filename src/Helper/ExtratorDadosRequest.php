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

        $currentPage = 1;
        if (array_key_exists("page",$queryString)) {
            $currentPage = $queryString['page'];
            unset($queryString["page"]);
        }

        $itemsPerPage = 5;
        if (array_key_exists("itemsPerPage", $queryString)) {
            $itemsPerPage = $queryString["itemsPerPage"];
            unset($queryString["itemsPerPage"]);
        }

        $filterInfo = $queryString;

        return [$sortInfo, $filterInfo, $currentPage, $itemsPerPage];
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