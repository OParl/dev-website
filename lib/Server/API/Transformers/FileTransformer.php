<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\File;

class FileTransformer extends BaseTransformer
{
    public function transform(File $file)
    {
        return [];
    }
}
