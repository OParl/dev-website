<?php

namespace App\Http\Controllers;

use App\Repositories\SchemaRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SchemaController extends Controller
{
    /**
     * @var SchemaRepository
     */
    protected $schemaRepository;

    public function __construct(SchemaRepository $schemaRepository)
    {
        $this->schemaRepository = $schemaRepository;
    }

    public function index()
    {
        return $this->jsonResponse($this->schemaRepository->all());
    }

    public function listSchemaVersion($version)
    {
        return $this->jsonResponse($this->schemaRepository->getVersion($version));
    }

    public function getSchema(Filesystem $fs, $version, $entity)
    {
        try {
            $schema = $this->schemaRepository->loadSchemaForEntity($version, $entity);

            return $this->jsonResponse($schema);
        } catch (\Exception $e) {
            return response('File not found.', 404, ['Content-type' => 'text/plain']);
        }
    }

    protected function jsonResponse(array $responseData): JsonResponse
    {
        return response()->json(
            $responseData,
            Response::HTTP_OK,
            [
                'Content-Type'                => 'application/json; charset=utf-8',
                'Access-Control-Allow-Origin' => '*',
            ],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }
}
