<?php

namespace OParl\Server\API\Transformers;

use OParl\Server\Model\File;

class FileTransformer extends BaseTransformer
{
    public function transform(File $file)
    {
        return array_merge($this->getDefaultAttributesForEntity($file), [
            'keyword' => $file->keywords->pluck('human_name'),
            
        ]);
    }
}
