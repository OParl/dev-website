<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\File;

class FileTransformer extends BaseTransformer
{
    public function transform(File $file)
    {
        return [
            'id'      => route('api.v1.file.show', $file),
            'type'    => 'http://spec.oparl.org/spezifikation/1.0/#entity-file',
            'keyword' => $file->keywords->pluck('human_name'),
        ];
    }
}
