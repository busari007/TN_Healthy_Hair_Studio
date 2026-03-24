<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Booking;

class ServicesController extends Controller
{
public function bookedDates()
{
    $bookings = \App\Models\Booking::where('status', 'approved')->get();

    $grouped = [];

    foreach ($bookings as $b) {
        $key = "{$b->day}-{$b->month}-{$b->year}";
        $grouped[$key][$b->staff][] = $b->time;
    }

    $fullyBookedDates = [];

    foreach ($grouped as $key => $staffBookings) {
        $allStaffFull = true;

        foreach (['Mrs Ebun', 'Stephanie', 'Ayomide'] as $staff) {
            $times = $staffBookings[$staff] ?? [];

            if (!(in_array('9:00AM', $times) && in_array('12:00PM', $times))) {
                $allStaffFull = false;
                break;
            }
        }

        if ($allStaffFull) {
            [$day, $month, $year] = explode('-', $key);

            $fullyBookedDates[] = [
                'day' => (int)$day,
                'month' => (int)$month,
                'year' => (int)$year,
            ];
        }
    }

    return response()->json([
        'bookedDates' => $fullyBookedDates
    ]);
}


public function checkStaffAvailability(Request $request)
{
    $staff = $request->staff;

    $bookings = \App\Models\Booking::where([
        'staff' => $staff,
        'day' => $request->day,
        'month' => $request->month,
        'year' => $request->year,
        'status' => 'approved',
    ])->pluck('time')->map(function ($time) {
    return strtoupper(str_replace(' ', '', $time));
});

    return response()->json([
        'bookedTimes' => $bookings
    ]);
}

public function bookedTimes(Request $request)
{
    $bookings = \App\Models\Booking::where([
        'staff'  => $request->staff,
        'day'    => $request->day,
        'month'  => $request->month,
        'year'   => $request->year,
        'status' => 'approved', // 👈 Only pull approved bookings
    ])
    ->pluck('time')
    ->map(function ($time) {
        return strtoupper(str_replace(' ', '', $time));
    });

    return response()->json([
        'bookedTimes' => $bookings
    ]);
}

public function store(Request $request)
{
    try {
        $booking = \App\Models\Booking::create([
            'service' => $request->service,
            'amount' => (int) $request->amount, // Force to integer
            'day' => (int) $request->day,
            'month' => (int) $request->month,
            'year' => (int) $request->year,
            'staff' => $request->staff,
            'time' => $request->time,
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        // This will return the REAL error message to your browser console
        return response()->json([
            'error' => $e->getMessage() 
        ], 400);
    }
}

public function getBookings(Request $request)
{
    if ($request->ajax()) {
        $user = Auth::user();
        $query = \App\Models\Booking::select(['id', 'service', 'staff', 'day', 'month', 'year', 'time', 'status', 'user_id', 'created_at']);

        // ... existing role filtering logic ...

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('date', function($row){
                return "{$row->day}/{$row->month}/{$row->year}";
            })
            // Style the status column with Tailwind Badges
            ->editColumn('status', function($row) {
                $status = strtolower($row->status);
                
                // Define colors based on status
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
            // Important: Tell DataTables to render the HTML for both columns
            ->rawColumns(['status', 'action']) 
            ->make(true);
    }
    
    return view('bookings');
}


public function updateStatus($id, Request $request)
{
    // 1. Validate the incoming status
    $request->validate([
        'status' => 'required|in:approved,rejected'
    ]);

    // 2. Find the booking
    $booking = \App\Models\Booking::findOrFail($id);

    // 3. Prevent re-processing if already approved or rejected
    if ($booking->status !== 'pending') {
        return response()->json([
            'error' => 'This booking has already been ' . $booking->status . ' and cannot be changed.'
        ], 422); // 422 Unprocessable Entity
    }

    // 4. Security check for Staff
    if (Auth::user()->role === 'staff' && $booking->staff !== Auth::user()->name) {
        return response()->json(['error' => 'You can only manage your own bookings.'], 403);
    }

    // 5. Update and Save
    $booking->status = $request->status;
    $booking->save();

    return response()->json([
        'success' => true,
        'message' => 'Booking ' . ucfirst($request->status) . ' successfully!'
    ]);
}

}
