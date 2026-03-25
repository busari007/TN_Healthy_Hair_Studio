<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\GeneralNotification;
use App\Models\User;
use App\Mail\BookingCreatedMail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

class PaymentController extends Controller
{

public function initialize(Request $request)
{
    $response = Http::withoutVerifying() 
        ->withToken(env('PAYSTACK_SECRET_KEY'))
        ->post('https://api.paystack.co/transaction/initialize', [
            'email' => $request->email,
            'amount' => $request->amount * 100, // Kobo
            'callback_url' => route('payment.callback'), 
            'metadata' => [
                'booking' => $request->all()
            ]
        ]);

    // Log this to your storage/logs/laravel.log to see what Paystack actually says
    // Log::info('Paystack Response:', $response->json());

    return $response->json();
}



public function webhook(Request $request)
{
    // Log::info('--- PAYSTACK WEBHOOK START ---');

    $secret = env('PAYSTACK_SECRET_KEY');
    $signature = $request->header('x-paystack-signature');
    $computed = hash_hmac('sha512', $request->getContent(), $secret);

    if ($computed !== $signature) {
        Log::error('Webhook Error: Invalid signature.');
        return response()->json(['error' => 'Invalid signature'], 400);
    }

    $event = $request->all();

    if (isset($event['event']) && $event['event'] === 'charge.success') {
        $data = $event['data'];
        
        // DEBUG: Check if reference exists in the payload
        // Log::info('Paystack Reference Received: ' . ($data['reference'] ?? 'MISSING'));
        // Log::info('Full Data Payload:', $data);

        $bookingData = $data['metadata']['booking'];

        try {
            // 1. Find User by email from Paystack payload
            $user = \App\Models\User::where('email', $data['customer']['email'])->first();
            
            if (!$user) {
                Log::error('Webhook Error: User not found.');
                return response()->json(['status' => 'ok']);
            }

            // 2. Create Booking
            $booking = \App\Models\Booking::create([
                'service' => $bookingData['service'],
                'amount'  => (int) $bookingData['amount'],
                'day'     => (int) $bookingData['day'],
                'month'   => (int) $bookingData['month'],
                'year'    => (int) $bookingData['year'],
                'staff'   => $bookingData['staff'],
                'time'    => $bookingData['time'],
                'user_id' => $user->id,
                'status'  => 'pending',
                'payment_reference' => $data['reference'],
                'reminder_sent' => false,
            ]);

            // Log::info('SUCCESS: Booking #' . $booking->id . ' created with Ref: ' . $booking->payment_reference);

            // 3. START NOTIFICATIONS & EMAILS
            // Use $bookingData and $user (the customer), NOT Auth::user() or $request
            $details = [
                'title'   => "New Booking: " . $bookingData['service'],
                'message' => "{$user->name} booked for {$bookingData['day']}/{$bookingData['month']} at {$bookingData['time']}",
                'by'      => $user->name,
                'amount'  => '₦' . number_format($bookingData['amount']),
            ];

            // Notify Admins and the specific Staff member
            $recipients = \App\Models\User::where('role', 'admin')
                ->orWhere('name', $bookingData['staff'])
                ->get();

            foreach ($recipients as $recipient) {
                $notificationData = $details;
                $notificationData['url'] = ($recipient->role === 'admin') ? '/admin' : '/bookings/list';
                $recipient->notify(new \App\Notifications\GeneralNotification($notificationData));
            }

            // Send the Email
            $emailList = $recipients->pluck('email')->toArray();
            if (!empty($emailList)) {
                \Illuminate\Support\Facades\Mail::to($emailList)->send(new \App\Mail\BookingCreatedMail($booking));
            }

        } catch (\Exception $e) {
            Log::error('WEBHOOK PROCESSING ERROR: ' . $e->getMessage());
        }
    }

    return response()->json(['status' => 'ok']);
}

}
