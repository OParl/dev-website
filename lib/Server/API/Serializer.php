<?php

namespace OParl\Server\API;

use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\ArraySerializer;

class Serializer extends ArraySerializer
{
    public function collection($resourceKey, array $data)
    {
        return [($resourceKey) ? $resourceKey : 'data' => $data];
    }
}