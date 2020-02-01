<?php
/**
 * @copyright 2018
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace App\Repositories;

use App\Services\OParlVersions;
use Illuminate\Contracts\Filesystem\Filesystem;


/**
 * Class SchemaRepository
 *
 * Operations on stored schema files
 */
class SchemaRepository
{
    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var OParlVersions
     */
    protected $oparlVersions;

    public function __construct(Filesystem $filesystem, OParlVersions $oparlVersions)
    {
        $this->fs = $filesystem;
        $this->oparlVersions = $oparlVersions;
    }

    public function all(): array
    {
        return collect($this->oparlVersions->getModule('specification'))->map(
            function ($_, $version) {
                return route('schema.list', compact('version'));
            }
        )->all();
    }

    public function getVersion($version): array
    {
        return collect($this->fs->files("schema/{$version}"))
            ->map(
                function ($file) use ($version) {
                    return route('schema.get', [$version, basename($file, '.json')]);
                }
            )->toArray();
    }

    /**
     * In OParl 1.0 some entities' schema definitions were not yet
     * first party definitions but instead embedded into other entities.
     *
     * If e.g. the schema for oparl:LegislativeTerm is requested,
     * the schema for oparl:Body must be returned instead.
     *
     * @param $requestedEntity
     * @return string
     */
    protected function mapRequestedEntityForOParlVersion10(string $requestedEntity): string
    {
        switch ($requestedEntity) {
            case 'LegislativeTerm':
                return 'Body';

            case 'Membership':
                return 'Person';

            case 'AgendaItem':
                return 'Meeting';

            case 'Consultation':
                return 'Paper';

            default:
                return $requestedEntity;
        }
    }

    public function loadSchemaForEntity($version, $entity): array
    {
        $loadEntity = $entity;

        if ('1.0' === $version) {
            $loadEntity = $this->mapRequestedEntityForOParlVersion10($entity);
        }

        $schema = $this->fs->get("schema/{$version}/$loadEntity.json");
        $schema = json_decode($schema, true);

        if ($entity !== $loadEntity) {
            $schema = $schema['properties'][lcfirst($entity)];
        }

        return $schema;
    }
}
