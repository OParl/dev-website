<?php

namespace OParl\Server\API\Transformers;

use OParl\Server\Model\File;

class FileTransformer extends BaseTransformer
{
    public function transform(File $file)
    {
        return array_merge($this->getDefaultAttributesForEntity($file), [
            'id'      => route('api.v1.file.show', $file),
            'type'    => 'https://schema.oparl.org/1.0/File',
            'keyword' => $file->keywords->pluck('human_name'),
        ]);
    }
}
