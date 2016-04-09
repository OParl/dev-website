<?php

namespace OParl\Server\API;

use EFrane\Transfugio\Transformers\SanitizedDataArraySerializer;

class Serializer extends SanitizedDataArraySerializer
{
    protected function reformatData(array $data)
    {
        $data = parent::reformatData($data);

        // remove deleted key if deleted is false
        if (array_key_exists('deleted', $data) && !$data['deleted']) {
            unset($data['deleted']);
        }

        // fix nested 'data' arrays
        foreach ($data as $key => $value) {
            if (!is_integer($key) && is_array($value) && array_key_exists('data', $value)) {
                $data[$key] = $value['data'];
            }
        }

        return $data;
    }
}