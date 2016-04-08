<?php

namespace OParl\Server\API;

use EFrane\Transfugio\Transformers\SanitizedDataArraySerializer;
use OParl\Server\Model\Keyword;

class Serializer extends SanitizedDataArraySerializer
{
    protected function reformatData(array $data)
    {
        $data = parent::reformatData($data);

        // reformat keywords
        if (array_key_exists('keyword', $data)) {
            $data['keyword'] = $data['keyword']->map(function (Keyword $keyword) {
                return $keyword->human_name;
            });
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