<?php

namespace App\Mail;

use App\Models\EvidenceRelsInspection;
use App\Models\Inspection;
use App\Models\InspectionConvoy;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\In;

class ReporteEmail extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance
   */
  public $inspection;
  public $convoy;
  public $evidences;
  public $user;
  public function __construct($inspectionId)
  {

    $this->inspection = Inspection::with([
      'inspectionType',
      'enterpriseSupplier',
      'enterpriseTransport',
      'checkpoint',
      'targeted',
      'category'
    ])->find($inspectionId);



    $this->convoy = InspectionConvoy::where('id_inspections', $inspectionId)->first();


    $this->evidences = EvidenceRelsInspection::where('id_inspections', $inspectionId)->get();


    $this->user = User::find($this->inspection->id_users);
  }


  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'Reporte Email',
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    return new Content(
      view: 'emails.ReporteEmail',
    );
  }

  /**
   * Get the attachments for the message.
   *
   * @return array<int, \Illuminate\Mail\Mailables\Attachment>
   */
  public function attachments(): array
  {
    return [];
  }
}
