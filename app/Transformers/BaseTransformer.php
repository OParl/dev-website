<?php

namespace App\Transformers;

use App\Model\OParl10BaseModel;
use App\Model\OParl10System;
use EFrane\Transfugio\Transformers\BaseTransformer as TransfugioBaseTransformer;

class BaseTransformer extends TransfugioBaseTransformer
{
    protected function getDefaultAttributesForEntity(OParl10BaseModel $entity)
    {
        $entityName = $entity->getModelName();
        $EntityName = ucfirst(class_basename($entity));

        $default = [
            'id'       => route("api.oparl.v1.{$entityName}.show", $entity),
            'type'     => "https://schema.oparl.org/1.0/{$EntityName}",
            'web'      => route("api.oparl.v1.{$entityName}.show", $entity, ['format' => 'html']),
            'deleted'  => $entity->trashed() ? true : false,
            'created'  => $this->formatDateTime($entity->created_at),
            'modified' => $this->formatDateTime($entity->updated_at),
        ];

        $defaultIncluded = [
            'created'  => $this->formatDateTime($entity->created_at),
            'modified' => $this->formatDateTime($entity->updated_at),
        ];

        // TODO: should system get an optional keyword property?
        if (!$entity instanceof OParl10System) {
            $default['keyword'] = $entity->keywords->pluck('human_name');
        }

        $attributes = ($this->isIncluded()) ? array_merge($default, $defaultIncluded) : $default;

        return $attributes;
    }

    public function cleanupData(array $attributes, OParl10BaseModel $entity)
    {
        if ($entity->trashed()) {
            $attributes = array_only($attributes, ['id', 'type', 'created', 'modified', 'deleted']);
            $attributes['modified'] = $this->formatDateTime($entity->deleted_at);
            $this->setDefaultIncludes([]);
        }

        return remove_empty_keys($attributes);
    }
}
