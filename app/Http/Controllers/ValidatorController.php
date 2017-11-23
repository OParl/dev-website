<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidationRequest;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Request;
use OParl\Spec\Jobs\ValidatorRunJob;

class ValidatorController extends Controller
{
    public function validationForm()
    {
        return view('developers.validation.form');
    }

    public function scheduleValidation(ValidationRequest $response)
    {
        // 1) read input data
        $endpoint = $response->input('endpoint');
        $email = $response->input('email');
        $canSaveData = (bool) $response->input('save');

        // 2) schedule validation job
        $this->dispatch(new ValidatorRunJob($endpoint, $email, $canSaveData));

        // 3) success!
        return redirect()->route('validator.index')->with('message',
            trans('app.validation.success', compact('endpoint')));
    }

    public function result(Request $request, $endpoint)
    {
        // TODO: show validation results for endpoint if they were stored
        // TODO: show validation progress maybe?
    }

    public function resultTest(Request $request, Filesystem $fs)
    {
        $pdf = new Dompdf([
            'isPhpEnabled' => true,
        ]);

        Carbon::setLocale('de');

        $title = sprintf('OParl Validator %s', Carbon::now()->format('hi-d-m-Y'));

        $data = [
            'endpoint'       => 'http://dev.dev-website.dev/api/v1/system/1',
            'result'         => json_decode($fs->get('result.json'), true),
            'validationDate' => Carbon::now()->format('d.m.Y'),
            'title'          => $title,
            'oparlVersion'   => '1.0',
        ];

        $html = view('developers.validation.result', $data)->render();

        if ($request->has('format') && $request->input('format') === 'html') {
            return $html;
        }

        $pdf->loadHtml($html);

        $pdf->setPaper('A4', 'portrait');

        $pdf->render();

        return response($pdf->output(), 200, [
            'Content-type'        => 'application/pdf',
            'Content-disposition' => "filename={$title}.pdf",
        ]);
    }
}
