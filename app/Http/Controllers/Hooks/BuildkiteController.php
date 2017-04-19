<?php

namespace App\Http\Controllers\Hooks;

class BuildkiteController extends HooksController
{
    public function storeBuild($buildableName)
    {
    }

    /*
     *  OLD BK Hook Code
     *
     *
     * Buildkite Deploy Hook.
     *
     * This hook is hit by the Buildkite deploy process of the specification
     * project. It is called in two different situations:
     *
     * Whenever an update happens to the spec, it is automatically built by
     * Buildkite and sent to this hook
     *
     * @param VersionUpdateRequest $request
     * @param Filesystem           $fs
     * @param BuildRepository      $buildRepository
     *
     * @return \Illuminate\Http\JsonResponse
     *
    public function addVersion(
        VersionUpdateRequest $request,
        BuildRepository $buildRepository
    ) {
        try {
            $hash = $request->input('version');
            $build = $buildRepository->getWithHash($hash);

            if (count($request->file('zip')) !== 1) {
                throw new \BadMethodCallException('Expected a file');
            }

            $zipFile = $request->file('zip');
            if (!ends_with($zipFile->getClientOriginalExtension(), 'zip')) {
                throw new \BadMethodCallException('Expected a zipfile');
            }

            $zipFile->move($build->storage_path, $build->zip_filename);
            $this->dispatch(new ExtractSpecificationBuildJob($build));

            return response()->json([
                'version' => $request->input('version'),
                'success' => true,
            ]);
        } catch (\Exception $e) {
            \Log::critical($e);

            return response()->json([
                'version'   => $request->input('version'),
                'success'   => false,
                'exception' => sprintf(
                    '%s in %s (%d)',
                    $e->getMessage(),
                    basename($e->getFile()),
                    $e->getLine()
                ),
            ]);
        }
    }
     */
}
