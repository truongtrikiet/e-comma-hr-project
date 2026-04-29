<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\CandidateScreening;

class CandidateScreeningResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public CandidateScreening $candidateScreening;

    public array $interview = [];

    /**
     * Create a new message instance.
     */
    public function __construct(CandidateScreening $candidateScreening, array $interview = [])
    {
        $this->candidateScreening = $candidateScreening;
        $this->interview = $interview;
    }

    public function build()
    {
        return $this->subject('Candidate Screening Result')
                    ->view('mails.candidate_screening_result', [
                        'candidateScreening' => $this->candidateScreening,
                        'interview' => $this->interview,
                    ]);
    }
}
