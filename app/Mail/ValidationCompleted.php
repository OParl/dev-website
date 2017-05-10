<?php

namespace App\Mail;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ValidationCompleted extends Mailable
{
    use Queueable, SerializesModels;

    protected $validationResult = [];
    protected $recipient = '';

    /**
     * Create a new message instance.
     *
     * @param array  $validationResult
     * @param string $recipient
     */
    public function __construct(array $validationResult, $recipient)
    {
        $this->validationResult = $validationResult;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $validationFilename = sprintf('OParl Validator %s.pdf', Carbon::now()->format('hi-d-m-Y'));

        $pdf = $this->createValidationResultPDF();

        return $this
            ->from('info@oparl.org', 'OParl Team')
            ->subject(trans('app.validation.completed'))
            ->text('emails.validation_completed')
            ->attachData($pdf->output(), $validationFilename, [
                'mime' => 'application/pdf',
            ])
            ->to($this->recipient);
    }

    public function createValidationResultPDF()
    {
        $pdf = new Dompdf();

        $pdf->loadHtml(view('developers.validation_result', [
            'result' => $this->validationResult,
        ]));

        $pdf->setPaper('A4', 'portrait');

        $pdf->render();

        return $pdf;
    }
}
