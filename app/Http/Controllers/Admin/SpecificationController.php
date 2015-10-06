<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\UpdateLiveCopy;
use Illuminate\Contracts\Filesystem\Filesystem;
use OParl\Spec\BuildRepository;
use OParl\Spec\Jobs\UpdateAvailableSpecificationVersionsJob;
use OParl\Spec\Model\SpecificationBuild;

class SpecificationController extends Controller
{
  public function index(BuildRepository $buildRepository)
  {
    $data = [
      'lastModified' => [
        'livecopy' => app('LiveCopyRepository')->getLastModified(),
        'versions' => $buildRepository->getLastModified(),
      ],
      'builds' => SpecificationBuild::orderBy('created_at', 'desc')->paginate(15)
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
        $this->dispatch(new UpdateAvailableSpecificationVersionsJob);
        $message = sprintf($message, 'Versionslisten');
        break;

      default:
        $message = "Es existiert keine Updatemethode für {$what}.";
        break;
    }

    return redirect()->route('admin.specification.index')->with('info', $message);
  }

  public function clean($what, BuildRepository $buildRepository)
  {
    $message = 'Die Bereinigung wurde erfolgreich gestartet.';

    /*
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
    */

    // FIXME: Implement this using the new jobs

    return redirect()->route('admin.specification.index')->with('info', $message);
  }

  public function delete(Filesystem $fs, $hash)
  {
    // TODO: implement deleting a single version
    $message = 'Die Löschung wurde erfolgreich durchgeführt.';

    return redirect()->route('admin.specification.index')->with('info', $message);
  }

  public function fetch($what, BuildRepository $buildRepository)
  {
    $message = 'Der Ladevorgang wurde erfolgreich gestartet.';

    if ($what === '_missing_')
    {
      $buildRepository->fetchMissing();
    } else
    {
      $buildRepository->fetchWithHash($what);
    }

    return redirect()->route('admin.specification.index')->with('info', $message);
  }
}