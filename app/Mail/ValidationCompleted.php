<?php

namespace App\Mail;

use Carbon\Carbon;
use Dompdf\Css\Stylesheet;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ValidationCompleted extends Mailable
{
    use Queueable, SerializesModels;

    protected $validationResult = [];

    /**
     * Create a new message instance.
     *
     * @param array  $validationResult
     * @param string $recipient
     */
    public function __construct($validationResult)
    {
        $this->validationResult = $validationResult;
    }

    /**
     * Build the message.
     *
     * @param $log Log
     * @return $this
     */
    public function build(Log $log, Filesystem $fs)
    {
        $validationFilename = sprintf('OParl Validator %s.pdf', Carbon::now()->format('hi-d-m-Y'));

        $log->info("Preparing validtion result email for {$this->validationResult['endpoint']}");
        $pdf = $this->createValidationResultPDF($log);
        $log->info("Finished preparing validtion result email for {$this->validationResult['endpoint']}");

        $pdf->stream(storage_path('app/validation/result.pdf'));

        return $this
            ->from('info@oparl.org', 'OParl Team')
            ->subject(trans('app.validation.completed'))
            ->text('emails.validation_completed', $this->validationResult)
            ->attachData($pdf->output(), $validationFilename, [
                'mime' => 'application/pdf',
            ]);
    }

    public function createValidationResultPDF(Log $log)
    {
        $options = new Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setTempDir(storage_path('app/validation'));

        if (in_array(app()->environment(), ['local', 'testing'])) {
            $options->setLogOutputFile(storage_path('logs/pdfgen.log'));
            $options->setDebugKeepTemp(true);
        }

        $pdf = new Dompdf($options);

        $stylesheetFilename = 'css/pdf.css';
        if (app()->environment('production')) {
            $stylesheetFilename = 'css/pdf.min.css';
        }

        $stylesheet = new Stylesheet($pdf);
        $stylesheet->load_css_file(public_path($stylesheetFilename));

        $html = view('developers.validation.result', $this->validationResult)->render();

        $pdf->setCss($stylesheet);
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $pdf;
    }
}
