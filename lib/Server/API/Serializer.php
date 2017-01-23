<?php

namespace OParl\Server\API;

use EFrane\Transfugio\Transformers\SanitizedArraySerializer;
use League\Fractal\Pagination\PaginatorInterface;

class Serializer extends SanitizedArraySerializer
{
    public function collection($resourceKey, array $data)
    {
        $data = parent::collection($resourceKey, $data);

        if ($resourceKey === 'included') {
            return $data['included'];
        }

        return ['data' => $data['data']];
    }

    public function item($resourceKey, array $data)
    {
        unset($data['included']);

        return parent::item($resourceKey, $data);
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
            'pagination' => collect([
                'totalElements'   => (int) $paginator->getTotal(),
                'elementsPerPage' => (int) $paginator->getPerPage(),
                'totalPages'      => (int) $totalPages,
                'currentPage'     => (int) $paginator->getCurrentPage(),
            ])->filter(function ($element) {
                return !is_null($element);
            })->toArray(),

            'links' => collect([
                'first' => $paginator->getUrl(1),
                'prev'  => $paginator->getUrl($paginator->getCurrentPage() - 1),
                'self'  => $paginator->getUrl($paginator->getCurrentPage()),
                'next'  => ($paginator->getCurrentPage() === $paginator->getLastPage()) ? null : $nextPage,
                'last'  => $paginator->getUrl($totalPages),
            ])->filter(function ($element) {
                return !is_null($element);
            })->toArray(),
        ]];
    }
}
