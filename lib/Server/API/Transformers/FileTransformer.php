<?php

namespace OParl\Server\API\Transformers;

use OParl\Server\Model\File;

class FileTransformer extends BaseTransformer
{
    public function transform(File $file)
    {
        $data = array_merge($this->getDefaultAttributesForEntity($file), [
            'keyword' => $file->keywords->pluck('human_name'),

        ]);

        return $this->cleanupData($data, $file);
    }
}
