<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Booking Reminder: ' . $this->booking->service)
                    ->view('emails.booking_reminder');
    }
}
