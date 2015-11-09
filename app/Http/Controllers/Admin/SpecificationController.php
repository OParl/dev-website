<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveSpecificationBuildRequest;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use OParl\Spec\BuildRepository;
use OParl\Spec\Jobs\UpdateAvailableSpecificationVersionsJob;
use OParl\Spec\Jobs\UpdateLiveVersionJob;
use OParl\Spec\LiveVersionRepository;
use OParl\Spec\Model\SpecificationBuild;

class SpecificationController extends Controller
{
    public function index(LiveVersionRepository $liveCopyRepository, BuildRepository $buildRepository)
    {
        $data = [
            'lastModified' => [
                'livecopy' => $liveCopyRepository->getLastModified(),
                'builds' => $buildRepository->getLastModified(),
            ],
            'builds' => SpecificationBuild::orderBy('created_at', 'desc')->paginate(15)
        ];

        return view('admin.specification.index', $data);
    }

    public function update($what)
    {
        $message = 'Das %supdate wurde erfolgreich gestartet.';

        switch ($what) {
            case 'livecopy':
                $this->dispatch(new UpdateLiveVersionJob());
                $message = sprintf($message, 'Livekopie-Pull');
                break;

            case 'livecopy-force':
                $this->dispatch(new UpdateLiveVersionJob(true));
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

    public function clean($what)
    {
        $message = "{$what} wurde ausgewählt.";

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

        if ($what === '_missing_') {
            $buildRepository->fetchMissing();
        } else {
            $buildRepository->fetchWithHash($what);
        }

        return redirect()->route('admin.specification.index')->with('info', $message);
    }

    public function edit($id)
    {
        try {
            $build = SpecificationBuild::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            abort(404, 'Dieser Build existiert nicht.');
        }

        return view('admin.specification.edit', compact('build'));
    }


    public function save(SaveSpecificationBuildRequest $request, $id)
    {
        /* @var $build SpecificationBuild */
        $build = SpecificationBuild::find($id);

        $build->update($request->except('_token', 'id'));
        $build->save();

        return redirect()->route('admin.specification.index')->with('message', "Succesfully saved {$id}");
    }
}
