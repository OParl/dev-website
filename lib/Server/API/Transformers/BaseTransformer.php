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
        $EntityName = ucfirst(class_basename($entity));

        $default = [
            'id'      => route("api.v1.{$entityName}.show", $entity),
            'type'    => "https://schema.oparl.org/1.0/{$EntityName}",
            'web'     => route("api.v1.{$entityName}.show", $entity, ['format' => 'html']),
            'deleted' => $entity->trashed() ? true : false,
            'created'  => $this->formatDateTime($entity->created_at),
            'modified' => $this->formatDateTime($entity->updated_at),
        ];

        $defaultIncluded = [
            'created'  => $this->formatDateTime($entity->created_at),
            'modified' => $this->formatDateTime($entity->updated_at),
        ];

        // TODO: should system get an optional keyword property?
        if (!$entity instanceof System) {
            $default['keyword'] = $entity->keywords->pluck('human_name');
        }

        $attributes = ($this->isIncluded()) ? array_merge($default, $defaultIncluded) : $default;

        return $attributes;
    }

    public function cleanupData(array $attributes, BaseModel $entity)
    {
        if ($entity->trashed()) {
            $attributes = array_only($attributes, ['id', 'type', 'created', 'modified', 'deleted']);
            $attributes['modified'] = $this->formatDateTime($entity->deleted_at);
            $this->setDefaultIncludes([]);
        }

        return remove_empty_keys($attributes);
    }
}
