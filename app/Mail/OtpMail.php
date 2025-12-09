<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    // 1. Definisikan properti publik untuk menyimpan kode OTP
    public $otpCode; 

    /**
     * Create a new message instance.
     */
    public function __construct(string $otpCode) // Menerima kode OTP dari Controller
    {
        // Menyimpan kode OTP ke properti publik
        $this->otpCode = $otpCode; 
    }

    /**
     * Get the message envelope (Mengatur Subjek Email).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi OTP Anda', // Subjek yang lebih deskriptif
        );
    }

    /**
     * Get the message content definition (Menghubungkan ke View).
     */
    public function content(): Content
    {
        return new Content(
            // 2. Ganti 'view.name' ke view Blade yang akan kita buat
            view: 'emails.otp', 
            
            // Properti publik ($otpCode) akan otomatis tersedia di view
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