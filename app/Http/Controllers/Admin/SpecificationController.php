<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Jobs\CleanVersions;
use App\Jobs\UpdateLiveCopy;
use App\Jobs\UpdateVersionHashes;
use Illuminate\Contracts\Filesystem\Filesystem;
use OParl\Spec\BuildRepository;

class SpecificationController extends Controller
{
  public function index(BuildRepository $buildRepository)
  {
    $data = [
      'lastModified' => [
        'livecopy' => app('LiveCopyRepository')->getLastModified(),
        'versions' => app('VersionRepository')->getLastModified(),
      ],
      'builds' => $buildRepository->getLatest(15)
    ];

    return view('admin.specification', $data);
  }

  public function update($what)
  {
    $message = 'Das %supdate wurde erfolgreich gestartet.';

    switch ($what)
    {
      case 'livecopy':
        $this->dispatch(new UpdateLiveCopy());
        $message = sprintf($message, 'Livekopie-Pull');
        break;

      case 'livecopy-force':
        $this->dispatch(new UpdateLiveCopy(true));
        $message = sprintf($message, 'Livekopie-Clone');
        break;

      case 'versions':
        $this->dispatch(new UpdateVersionHashes());
        $message = sprintf($message, 'Versionslisten');
        break;

      default:
        $message = "Es existiert keine Updatemethode für {$what}.";
        break;
    }

    return redirect()->route('admin.specification.index')->with('info', $message);
  }

  public function clean($what)
  {
    $message = 'Die Bereinigung wurde erfolgreich gestartet.';

    switch ($what)
    {
      case 'all':
      case 'extraneous':
        $this->dispatch(new CleanVersions($what));
        break;

      default:
        $message = "Es existiert keine Reinigungsmethode für {$what}.";
        break;
    }

    return redirect()->route('admin.specification.index')->with('info', $message);
  }

  public function delete(Filesystem $fs, $hash)
  {
    // TODO: implement deleting a single version
    $message = 'Die Löschung wurde erfolgreich durchgeführt.';

    return redirect()->route('admin.specification.index')->with('info', $message);
  }

  public function fetch($what)
  {
    $message = 'Der Ladevorgang wurde erfolgreich gestartet.';

    if ($what === '_missing_')
    {
      // TODO: fetch all missing versions
    } else
    {
      // TODO: only fetch the one in question
    }

    return redirect()->route('admin.specification.index')->with('info', $message);
  }
}