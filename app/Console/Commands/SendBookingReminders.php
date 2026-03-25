<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\User;
use App\Mail\BookingReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:send-reminders';
    protected $description = 'Send email reminders 12 hours before a booking';

    public function handle()
    {
 // Only get approved bookings that HAVEN'T had a reminder sent yet
    $bookings = Booking::where('status', 'approved')
                       ->where('reminder_sent', false) 
                       ->get();

    foreach ($bookings as $booking) {
    $bookingString = "{$booking->day}-{$booking->month}-{$booking->year} {$booking->time}";
    $bookingTime = \Carbon\Carbon::createFromFormat('j-n-Y g:iA', $bookingString);

    $hoursDiff = now()->diffInHours($bookingTime, false);
    
    // DEBUG: This prints to your terminal when you run the command
    $this->info("Checking Booking #{$booking->id}: {$hoursDiff} hours away.");

        // Check if the appointment is within the 12-hour window
        if (now()->diffInHours($bookingTime, false) <= 13 && now()->isBefore($bookingTime)) {
               
                // Collect Recipients
                $emails = [];
                
                // Add Admins
                $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
                
                // Add Staff
                $staffEmail = User::where('name', $booking->staff)->value('email');
                
                // Add Client
                $clientEmail = User::where('id', $booking->user_id)->value('email');

                $emails = array_unique(array_filter(array_merge($adminEmails, [$staffEmail, $clientEmail])));

                // Send Mail
                if (!empty($emails)) {
                    Mail::to($emails)->send(new BookingReminderMail($booking));
                }

                // Marks as sent immediately so it's not picked up again
                $booking->update(['reminder_sent' => true]);
            }
        }
    }
}
