<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\UploadedFile;

class lifReport extends Mailable
{
  use Queueable, SerializesModels;

  public $description;
  public $date;
  public $imgOne;
  public $imgTwo;

  public function __construct($description, $date, $imgOne, $imgTwo)
  {
    $this->description = $description;
    $this->date = $date;
    $this->imgOne = $imgOne;
    $this->imgTwo = $imgTwo;
  }


  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'Reporte de Levantamiento de Inspección',
    );
  }

  public function content(): Content
  {
    return new Content(
      markdown: 'emails.lifReport',
      with: [
        'description' => $this->description,
        'date'        => $this->date,
        'imgOne'      => $this->imgOne,
        'imgTwo'      => $this->imgTwo,
      ]
    );
  }

  // NO adjuntamos nada
  public function attachments(): array
  {
    return [];
  }
}
