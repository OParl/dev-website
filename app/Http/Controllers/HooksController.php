<?php namespace App\Http\Controllers;

use App\Http\Requests\VersionUpdateRequest;
use App\Jobs\UpdateLiveCopy;
use App\Jobs\UpdateVersionHashes;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use OParl\Spec\BuildRepository;
use OParl\Spec\Jobs\ExtractSpecificationBuildJob;
use OParl\Spec\Jobs\UpdateAvailableSpecificationVersionsJob;
use OParl\Spec\Jobs\UpdateLiveVersionJob;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Hooks Controller
 *
 * This website is kept up-to-date with the help of webhooks.
 * One of them (specChange) is responsible for updating the live copy if an eligible
 * update occurs in th GH spec repo. The other one (addVersion) is called whenever
 * a new spec version was built by Buildkite. The latter one makes downloads work.
 *
 * @package App\Http\Controllers
 **/
class HooksController extends Controller
{
    use DispatchesJobs;

    /**
     * GitHub Spec Change Webhook
     *
     * This hook is called by GitHub on certain Spec repository updates.
     * The webhook setup on GH should be the following:
     *
     * - Content-type: application/x-www-form-urlencoded
     * - Secret: should match env('GITHUB_WEBHOOK_SECRET')
     * - Events: push, pull_request
     *
     * (Other events may be enabled but will be ignored.)
     *
     * Validation of the WebHook is done via the
     * `ValidateGitHubWebHook` middleware.
     *
     * On being called, the hook checks if a push or
     * a merged pull request was signalled and updates
     * the live copy and the version repository accordingly.
     *
     * @param Request $request
     * @see \App\Http\Middleware\ValidateGitHubWebHook
     * @return \Illuminate\Http\JsonResponse
     **/
    public function specChange(Request $request)
    {
        switch ($request->header('x-github-event')) {
            case 'pull_request':
                // update jobs are only necessary on PR merges
                $json = json_decode($request->input('payload'), true);

                if ($json['action'] == 'closed' && $json['merged']) {
                    $this->dispatch(new UpdateLiveVersionJob());
                    $this->dispatch(new UpdateAvailableSpecificationVersionsJob());

                    return response()->json(['result' => 'Scheduled updates.']);
                }

                return response()->json(['result' => 'No merge happened. Nothing to do.']);

            case 'push':
                // just initiate spec and version updates
                $this->dispatch(new UpdateLiveVersionJob());
                $this->dispatch(new UpdateAvailableSpecificationVersionsJob());

                return response()->json(['result' => 'Scheduled updates.']);

            case 'ping':
            default:
                return response()->json(['result' => Inspiring::quote()]);
        }
    }

    /**
     * Buildkite Deploy Hook
     *
     * This hook is hit by the Buildkite deploy process of the specification
     * project. It is called in two different situations:
     *
     * Whenever an update happens to the spec, it is automatically built by
     * Buildkite and sent to this hook
     *
     * @param VersionUpdateRequest $request
     * @param Filesystem $fs
     * @param BuildRepository $buildRepository
     * @return \Illuminate\Http\JsonResponse
     **/
    public function addVersion(
        VersionUpdateRequest $request,
        BuildRepository $buildRepository
    )
    {
        try {
            $hash = $request->input('version');
            $build = $buildRepository->getWithHash($hash);

            collect($request->file())->each(function (UploadedFile $file) use ($build) {
                $ext = $file->getClientOriginalExtension();

                if (ends_with($ext, 'gz')) {
                    $file->move($build->storage_path, $build->tar_gz_filename);
                }

                if (ends_with($ext, 'bz2')) {
                    $file->move($build->storage_path, $build->tar_bz_filename);
                }

                if (ends_with($ext, 'zip')) {
                    $file->move($build->storage_path, $build->zip_filename);
                }
            });

            $this->dispatch(new ExtractSpecificationBuildJob($build));

            return response()->json([
                'version' => $request->input('version'),
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'version' => $request->input('version'),
                'success' => false,
                'exception' => sprintf(
                    '%s in %s (%d)',
                    $e->getMessage(),
                    basename($e->getFile()),
                    $e->getLine()
                )
            ]);
        }
    }
}
