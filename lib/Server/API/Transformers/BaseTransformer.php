<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer as TransfugioBaseTransformer;
use OParl\Server\Model\BaseModel;
use OParl\Server\Model\System;

class BaseTransformer extends TransfugioBaseTransformer
{
    protected function getDefaultAttributesForEntity(BaseModel $entity)
    {
        $entityName = $entity->getModelName();
        $EntityName = ucfirst($entityName);

        $default = [
            'id'      => route("api.v1.{$entityName}.show", $entity),
            'type'    => "https://schema.oparl.org/1.0/{$EntityName}",
            'web'     => route("api.v1.{$entityName}.show", $entity, ['format' => 'html']),
            'trashed' => $entity->trashed(),
        ];

        $defaultIncluded = [
            'created'  => $this->formatDate($entity->created_at),
            'modified' => $this->formatDate($entity->updated_at),
        ];

        // TODO: should system get an optional keyword property?
        if (!$entity instanceof System) {
            $default['keyword'] = $entity->keywords->pluck('human_name');
        }

        $attributes = ($this->isIncluded()) ? array_merge($default, $defaultIncluded) : $default;

        return $attributes;
    }
}