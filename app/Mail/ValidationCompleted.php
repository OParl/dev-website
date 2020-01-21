<?php

namespace App\Mail;

use Carbon\Carbon;
use Dompdf\Css\Stylesheet;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;

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
     * @param LoggerInterface $log
     *
     * @return $this
     */
    public function build(LoggerInterface $log)
    {
        $validationFilename = sprintf('OParl Validator %s.pdf', Carbon::now()->format('hi-d-m-Y'));

        $log->info("Preparing validtion result email for {$this->validationResult['endpoint']}");
        $pdf = $this->createValidationResultPDF($log);
        $log->info("Finished preparing validtion result email for {$this->validationResult['endpoint']}");

        file_put_contents(storage_path('app/validation/result.pdf'), $pdf->output());

        return $this
            ->from('bot@oparl.org', 'OParl Validator')
            ->replyTo('info@oparl.org', 'OParl Team')
            ->subject(trans('app.validation.completed', ['endpoint' => $this->validationResult['endpoint']]))
            ->text('emails.validation_completed', $this->validationResult)
            ->attachData($pdf->output(['compress' => 1]), $validationFilename, [
                'mime' => 'application/pdf',
            ]);
    }

    public function createValidationResultPDF(LoggerInterface $log)
    {
        $options = new Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setTempDir(storage_path('app/validation'));

        if (in_array(app()->environment(), ['local', 'testing'])) {
            $options->setLogOutputFile(storage_path('logs/pdfgen.log'));
        }

        $pdf = new Dompdf($options);

        $stylesheetFilename = 'css/pdf.css';
        if (app()->environment('production')) {
            $stylesheetFilename = 'css/pdf.min.css';
        }

        $stylesheet = new Stylesheet($pdf);
        $stylesheet->load_css_file(public_path($stylesheetFilename));

        $log->debug('Validation result', $this->validationResult);

        $html = view('developers.validation.result', $this->validationResult)->render();

        $pdf->setCss($stylesheet);
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $pdf;
    }
}
