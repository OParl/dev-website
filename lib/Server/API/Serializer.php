<?php

namespace OParl\Server\API;

use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Serializer\ArraySerializer;

class Serializer extends ArraySerializer
{
    public function collection($resourceKey, array $data)
    {
        return [($resourceKey) ? $resourceKey : 'data' => $data];
    }

    public  function meta(array $meta)
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