<?php

namespace OParl\Server\API;

use EFrane\Transfugio\Transformers\SanitizedArraySerializer;
use League\Fractal\Pagination\PaginatorInterface;

class Serializer extends SanitizedArraySerializer
{
    public function collection($resourceKey, array $data)
    {
        $data = parent::collection($resourceKey, $data);

        return [($resourceKey) ? $resourceKey : 'data' => $data['data']];
    }

    public function meta(array $meta)
    {
        if (!empty($meta)) {
            return $meta[0];
        }

        return $meta;
    }

    public function paginator(PaginatorInterface $paginator)
    {
        $nextPage = $paginator->getUrl($paginator->getCurrentPage() + 1);

        $totalPages = ceil($paginator->getTotal() / $paginator->getPerPage());

        return [[
            'pagination' => [
                'totalElements'   => (int) $paginator->getTotal(),
                'elementsPerPage' => (int) $paginator->getPerPage(),
                'totalPages'      => (int) $totalPages,
                'currentPage'     => (int) $paginator->getCurrentPage(),
            ],

            'links' => [
                'first' => $paginator->getUrl(1),
                'prev'  => $paginator->getUrl($paginator->getCurrentPage() - 1),
                'self'  => $paginator->getUrl($paginator->getCurrentPage()),
                'next'  => ($paginator->getCurrentPage() + 1 >= $paginator->getLastPage()) ? null : $nextPage,
                'last'  => $paginator->getUrl($totalPages),
            ],
        ]];
    }
}
