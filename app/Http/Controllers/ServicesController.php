<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Booking;
use App\Notifications\GeneralNotification;
use App\Models\User;
use App\Mail\BookingCreatedMail;
use App\Mail\BookingStatusMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ServicesController extends Controller
{
public function bookedDates() {
    $bookings = \App\Models\Booking::where('status', 'approved')->get();
    $grouped = [];
    
    foreach ($bookings as $b) {
        $key = "{$b->day}-{$b->month}-{$b->year}";
        $grouped[$key][$b->staff][] = $b->time;
    }

    $fullyBookedDates = [];
    $staffList = ['Mrs Ebun', 'Stephanie', 'Ayomide'];
    $slots = ['9:00AM', '12:00PM'];

    foreach ($grouped as $key => $staffBookings) {
        [$day, $month, $year] = explode('-', $key);
        $allStaffFull = true;

        foreach ($staffList as $staff) {
            $bookedTimes = $staffBookings[$staff] ?? [];
            
            foreach ($slots as $slot) {
                // Create a Carbon instance for the specific slot
                $slotTime = \Carbon\Carbon::createFromFormat('j-n-Y g:iA', "$key $slot");
                
                // If slot is NOT booked AND is more than 12 hours away, it's available
                if (!in_array($slot, $bookedTimes) && $slotTime->gt(now()->addHours(12))) {
                    $allStaffFull = false;
                    break 2; // Break both loops, this date is available
                }
            }
        }

        if ($allStaffFull) {
            $fullyBookedDates[] = [
                'day' => (int)$day,
                'month' => (int)$month,
                'year' => (int)$year,
            ];
        }
    }

    return response()->json(['bookedDates' => $fullyBookedDates]);
}

   public function redirectToWhatsApp(Request $request)
    {
        $serviceName = $request->query('service_name', 'a service');
        $phoneNumber = '2348076865464'; // International format without the '+'
        
        // Define your message template
        $message = "Hello, I would like to book the {$serviceName}. Is it available for booking?";
        
        // URL encode the message for the WhatsApp API
        $encodedMessage = urlencode($message);
        
        // Redirect the user to the WhatsApp "Click to Chat" link
        return redirect("https://wa.me/{$phoneNumber}?text={$encodedMessage}");
    }

public function checkStaffAvailability(Request $request) 
{
    $staff = $request->staff;
    $day = $request->day;
    $month = $request->month;
    $year = $request->year;

    // 1. Get actual approved bookings from the database
    $bookedInDb = \App\Models\Booking::where([
        'staff' => $staff,
        'day' => $day,
        'month' => $month,
        'year' => $year,
        'status' => 'approved',
    ])
    ->pluck('time')
    ->map(fn($time) => strtoupper(str_replace(' ', '', $time)))
    ->toArray();

    // 2. Define your standard working slots
    $availableSlots = ['9:00AM', '12:00PM'];
    $finalBookedTimes = $bookedInDb;

    // 3. Logic: If a slot is < 12 hours away, treat it as "booked" (unavailable)
    foreach ($availableSlots as $slot) {
        try {
            // Create a timestamp for the specific slot
            $slotDateTime = \Carbon\Carbon::createFromFormat('j-n-Y g:iA', "$day-$month-$year $slot");

            // If the slot is in the past OR less than 12 hours from now
            if ($slotDateTime->lt(now()->addHours(12))) {
                if (!in_array($slot, $finalBookedTimes)) {
                    $finalBookedTimes[] = $slot;
                }
            }
        } catch (\Exception $e) {
            // Handle invalid date formats if necessary
            continue;
        }
    }

    return response()->json([
        'bookedTimes' => array_values(array_unique($finalBookedTimes)),
        'message' => 'Some slots may be hidden because they require 12 hours notice.'
    ]);
}

public function bookedTimes(Request $request) {
    $day = $request->day;
    $month = $request->month;
    $year = $request->year;

    // 1. Get actual approved bookings
    $bookedInDb = \App\Models\Booking::where([
        'staff' => $request->staff,
        'day' => $day,
        'month' => $month,
        'year' => $year,
        'status' => 'approved',
    ])->pluck('time')->map(fn($t) => strtoupper(str_replace(' ', '', $t)))->toArray();

    // 2. Define your standard slots
    $allSlots = ['9:00AM', '12:00PM'];
    $finalBookedTimes = $bookedInDb;

    // 3. Add slots to the "booked" list if they are within 12 hours from now
    foreach ($allSlots as $slot) {
        $slotDateTime = \Carbon\Carbon::createFromFormat('j-n-Y g:iA', "$day-$month-$year $slot");
        
        if ($slotDateTime->lt(now()->addHours(12)) && !in_array($slot, $finalBookedTimes)) {
            $finalBookedTimes[] = $slot;
        }
    }

    return response()->json([
        'bookedTimes' => $finalBookedTimes,
        'message' => 'Slots within 12 hours are unavailable.'
    ]);
}

// public function store(Request $request)
// {
//     $requestedTime = "{$request->day}-{$request->month}-{$request->year} {$request->time}";
//     $bookingWindow = \Carbon\Carbon::createFromFormat('j-n-Y g:iA', $requestedTime);

//     if ($bookingWindow->lt(now()->addHours(12))) {
//         return response()->json([
//             'error' => 'Bookings must be made at least 12 hours in advance.'
//         ], 422);
//     }

//         $booking = \App\Models\Booking::create([
//             'service' => $request->service,
//             'amount' => (int) $request->amount, // Force to integer
//             'day' => (int) $request->day,
//             'month' => (int) $request->month,
//             'year' => (int) $request->year,
//             'staff' => $request->staff,
//             'time' => $request->time,
//             'user_id' => Auth::id(),
//             'status' => 'pending',
//         ]);

//         try {
//         $user = Auth::user();
        
//         // 1. Prepare Base Details
//         $details = [
//             'title'   => $request->service,
//             'message' => "{$user->name} booked for {$request->day}/{$request->month}/{$request->year} at {$request->time}",
//             'by'      => $user->name,
//             'amount'  => '₦' . number_format($request->amount),
//         ];

// // 1. Identify Recipients: Anyone with the role 'admin' OR the specific staff member
// $recipients = User::where('role', 'admin')
//     ->orWhere('name', $request->staff)
//     ->get();

// // 2. Send notifications with custom URLs based on their role
// foreach ($recipients as $recipient) {
//     $notificationData = $details;
    
//     // Check if the user has the 'admin' role for the URL
//     if ($recipient->role === 'admin') {
//         $notificationData['url'] = '/admin'; 
//     } else {
//         $notificationData['url'] = '/bookings/list';
//     }

//     $recipient->notify(new GeneralNotification($notificationData));
// }

//  $recipients = \App\Models\User::where('role', 'admin')
//             ->orWhere('name', $request->staff)
//             ->pluck('email')
//             ->toArray();

//         // 2. Send the Mail
//         if (!empty($recipients)) {
//             Mail::to($recipients)->send(new BookingCreatedMail($booking));
//         }

//         return response()->json(['success' => true]);

//     } catch (\Exception $e) {
//         return response()->json(['error' => $e->getMessage()], 400);
//     }
// }

public function getBookings(Request $request)
{
    if ($request->ajax()) {
        $user = Auth::user();
        
        $query = \App\Models\Booking::select([
            'id', 'service', 'staff', 'day', 'month', 'year', 
            'time', 'status', 'user_id', 'created_at', 'is_refunded'
        ])->latest(); 

        if ($user->role === 'admin') {
            // No extra filter
        } elseif ($user->role === 'staff') {
            $query->where('staff', $user->name); 
        } else {
            $query->where('user_id', $user->id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            // 1. Convert created_at to Africa/Lagos (WAT)
            ->editColumn('created_at', function($row) {
                return $row->created_at->timezone('Africa/Lagos')->toDateTimeString();
            })
            ->editColumn('date', function($row){
                return "{$row->day}/{$row->month}/{$row->year}";
            })
            // 2. Enhanced Styling for Status & Refunded
            ->editColumn('status', function($row) {
                if ($row->is_refunded) {
                    return '<span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border bg-amber-50 text-amber-600 border-amber-200 shadow-sm">
                                ↺ Refunded
                            </span>';
                }

                $status = strtolower($row->status);
                $classes = match($status) {
                    'approved' => 'bg-green-100 text-green-700 border-green-200',
                    'rejected' => 'bg-red-100 text-red-700 border-red-200',
                    'pending'  => 'bg-pink-100 text-pink-700 border-pink-200',
                    default    => 'bg-gray-100 text-gray-700 border-gray-200',
                };

                return '<span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border ' . $classes . '">' 
                        . ucfirst($row->status) . 
                       '</span>';
            })
            ->addColumn('action', function($row) {
                return view('partials.booking-actions', compact('row'))->render();
            })
            ->rawColumns(['status', 'action']) 
            ->make(true);
    }
    
    return view('bookings');
}



public function updateStatus($id, Request $request)
{
    $request->validate([
        'status' => 'required|in:approved,rejected'
    ]);

    $booking = \App\Models\Booking::findOrFail($id);
    $currentUser = Auth::user();

    if ($booking->status !== 'pending') {
        return response()->json(['error' => 'This booking is already ' . $booking->status], 422);
    }

    if ($currentUser->role === 'staff' && $booking->staff !== $currentUser->name) {
        return response()->json(['error' => 'You can only manage your own bookings.'], 403);
    }

    $booking->status = $request->status;
    $booking->save();

    // --- NOTIFICATION & MAIL LOGIC ---

    // 1. Notify the Client (Notification + Mail)
    $client = User::find($booking->user_id);
    if ($client) {
        $client->notify(new GeneralNotification([
            'title'   => "Booking " . ucfirst($request->status),
            'message' => "The {$booking->service} for {$booking->day}/{$booking->month} has been {$request->status}.",
            'by'      => $currentUser->name,
            'amount'  => '₦' . number_format($booking->amount),
            'url'     => '/bookings/list'
        ]));
        
        // Send status email to client
        Mail::to($client->email)->send(new BookingStatusMail($booking));
    }

    // 2. Identify Other Recipients for Mail (Admins and Assigned Staff)
    // We get admins and the specific staff member assigned to the booking
    $staffAndAdmins = User::where('role', 'admin')
        ->orWhere('name', $booking->staff)
        ->get();

    foreach ($staffAndAdmins as $recipient) {
        // Send In-App Notification (exclude the person who performed the action)
        if ($recipient->id !== $currentUser->id) {
            $recipient->notify(new GeneralNotification([
                'title'   => "Booking Update",
                'message' => "{$booking->service} for {$booking->day}/{$booking->month} is now {$request->status}.",
                'by'      => $currentUser->name,
                'amount'  => '₦' . number_format($booking->amount),
                'url'     => $recipient->role === 'admin' ? '/admin' : '/bookings/list'
            ]));
        }

        // Send Email to Admins and Staff (Always keep them in the loop)
        Mail::to($recipient->email)->send(new BookingStatusMail($booking));
    }

    return response()->json([
        'success' => true,
        'message' => 'Booking ' . ucfirst($request->status) . ' successfully!'
    ]);
}

public function refund($id) {
    $booking = \App\Models\Booking::with('user')->findOrFail($id);
    $currentUser = Auth::user();

    // 1. 12-Hour Rule Check
    $bookingTime = \Carbon\Carbon::createFromFormat('j-n-Y g:iA', "{$booking->day}-{$booking->month}-{$booking->year} {$booking->time}");
    
    // If current time is within 12 hours of the appointment
    if (now()->diffInHours($bookingTime, false) < 12) {
        return response()->json([
            'error' => 'Refunds are not allowed within 12 hours of the appointment.'
        ], 422);
    }

    // 2. Security & Status Checks
    if ($currentUser->role !== 'admin' && $booking->user_id !== $currentUser->id) {
        return response()->json(['error' => 'Unauthorized action.'], 403);
    }

    if ($booking->is_refunded) {
        return response()->json(['error' => 'This booking has already been refunded.'], 422);
    }

    return $this->executeRefund($booking, $currentUser);
}

/**
 * Extracted the actual Paystack call so it can be reused by updateStatus
 */
private function executeRefund($booking, $currentUser) {
    if (!$booking->payment_reference) {
        return response()->json(['error' => 'No payment reference found.'], 400);
    }

    $response = Http::withoutVerifying()
        ->withHeaders([
            'Authorization' => 'Bearer ' . trim(env('PAYSTACK_SECRET_KEY')),
            'Content-Type'  => 'application/json',
        ])
        ->post('https://api.paystack.co/refund', [
            'transaction' => $booking->payment_reference,
            'amount'      => (int)($booking->amount * 100) 
        ]);

    $data = $response->json();

    if (isset($data['status']) && $data['status'] === true) {
        $booking->update([
            'is_refunded' => true,
            'status' => 'rejected',
            'refund_id' => $data['data']['id'] ?? null
        ]);

        // --- START NOTIFICATIONS & EMAILS ---

        $details = [
            'title'   => "Booking Refunded",
            'message' => "The refund for {$booking->service} has been processed successfully.",
            'by'      => $currentUser->name,
            'amount'  => '₦' . number_format($booking->amount),
        ];

        // A. Notify the Client (The one getting the money)
        $client = $booking->user;
        if ($client) {
            $clientData = $details;
            $clientData['url'] = '/bookings/list';
            $client->notify(new \App\Notifications\GeneralNotification($clientData));
        }

        // B. Notify Admins and Staff
        $recipients = \App\Models\User::where('role', 'admin')
            ->orWhere('name', $booking->staff)
            ->get();

        foreach ($recipients as $recipient) {
            // Don't send a bell notification to the person who clicked "Refund"
            if ($recipient->id !== $currentUser->id) {
                $notifData = $details;
                $notifData['url'] = ($recipient->role === 'admin') ? '/admin' : '/bookings/list';
                $recipient->notify(new \App\Notifications\GeneralNotification($notifData));
            }
        }

        // C. Send the Status Email (Use your existing BookingStatusMail)
        $emailList = $recipients->pluck('email')->push($client->email)->unique()->toArray();
        if (!empty($emailList)) {
            \Illuminate\Support\Facades\Mail::to($emailList)->send(new \App\Mail\BookingStatusMail($booking));
        }

        return response()->json(['success' => true, 'message' => 'Refund processed successfully!']);
    }

    Log::error('Paystack Refund Failed', ['response' => $data]);
    return response()->json(['error' => $data['message'] ?? 'Refund failed'], 400);
}

}
