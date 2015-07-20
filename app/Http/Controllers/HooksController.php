<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Filesystem\Filesystem;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Inspiring;

use Illuminate\Http\Request;

use App\Http\Requests\VersionUpdateRequest;
use App\Jobs\UpdateLiveCopy;
use App\Jobs\UpdateVersionHashes;
use App\ScheduledBuild;


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
    switch ($request->header('x-github-event'))
    {
      case 'pull_request':
        // update jobs are only necessary on PR merges
        $json = json_decode($request->input('payload'), true);

        if ($json['action'] == 'closed' && $json['merged'])
        {
          $this->dispatch(new UpdateLiveCopy());
          $this->dispatch(new UpdateVersionHashes());

          return response()->json(['result' => 'Scheduled updates.']);
        }

        return response()->json(['result' => 'No merge happened. Nothing to do.']);

      case 'push':
        // just initiate spec and version updates
        $this->dispatch(new UpdateLiveCopy());
        $this->dispatch(new UpdateVersionHashes());

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
   * 1) Whenever an update happens to the spec, it is automatically built by
   *    Buildkite and sent to this hook
   *
   * 2) As a continuation of the scheduled builds, when BK finished building,
   *    this hook needs to check if the build was scheduled and act accordingly.
   *
   * @param VersionUpdateRequest $request
   * @param Filesystem $fs
   * @param Mailer $mailer
   * @return \Illuminate\Http\JsonResponse
   **/
  public function addVersion(VersionUpdateRequest $request, Filesystem $fs, Mailer $mailer)
  {
    try
    {
      $this->saveFiles($request, $fs);
      $this->handleScheduledBuilds($request, $mailer);

      return response()->json([
        'version' => $request->input('version'),
        'success' => true
      ]);
    } catch (\Exception $e)
    {
      return response()->json([
        'version'   => $request->input('version'),
        'success'   => false,
        'exception' => $e->getMessage()
      ]);
    }
  }

  /**
   * Checks if this was a scheduled build, sends email,
   * remove from scheduled builds
   *
   * @param VersionUpdateRequest $request
   * @param Mailer $mailer
   **/
  protected function handleScheduledBuilds(VersionUpdateRequest $request, Mailer $mailer)
  {
    $scheduledBuilds = ScheduledBuild::whereVersion($request->input('version'))->get();
    foreach ($scheduledBuilds as $build) {
      $mailer->send('emails.success', ['build' => $build], function ($m) use ($build) {
        $m->from('info@oparl.org');
        $m->to($build->email);
        $m->subject('[OParl.org] Ihre angeforderte Spezifikationsversion ist bereit zum Download!');
      });

      $build->delete();
    }
  }

  /**
   * Save the archives from the request and extract one to enable
   * quick access to the different output formats.
   *
   * @param VersionUpdateRequest $request
   * @param Filesystem $fs
   **/
  protected function saveFiles(VersionUpdateRequest $request, Filesystem $fs)
  {
    $version = substr($request->input('version'), 0, 7);

    $path = 'versions/' . $version . '/';
    $fs->makeDirectory($path, '0755', true, true);
    $fs->cleanDirectory($path);

    foreach ($request->file() as $file)
      $file->move(storage_path('app/' . $path), $file->getClientOriginalName());

    chdir(storage_path('app/' . $path));
    exec('tar -xzf *.tar.gz');
  }
}