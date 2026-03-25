<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function getUsers(Request $request)
{
    if ($request->ajax()) {
        $user = Auth::user();
        
        // 1. Add ->latest() here to order by created_at DESC
        $query = \App\Models\User::select(['id', 'name', 'email', 'phone', 'address', 'status', 'role', 'created_at'])
            ->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('status', function($row) {
                $status = strtolower($row->status);
                $classes = match($status) {
                    'enabled' => 'bg-green-100 text-green-700 border-green-200',
                    'disabled' => 'bg-red-100 text-red-700 border-red-200',
                    default    => 'bg-gray-100 text-gray-700 border-gray-200',
                };

                return '<span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border ' . $classes . '">' 
                        . ucfirst($row->status) . 
                       '</span>';
            })
            ->addColumn('action', function($row) {
                return view('partials.user-actions', compact('row'))->render();
            })
            ->rawColumns(['status', 'action']) 
            ->make(true);
    }
    
    return view('users');
}

public function updateStatus($id, Request $request)
{
    $request->validate([
        'status' => 'required|in:enabled,disabled'
    ]);

    // 1. Prevent self-restriction
    if ((int)$id === (int)Auth::id()) {
        return response()->json([
            'error' => 'You cannot disable your own account.'
        ], 403);
    }

    $user = \App\Models\User::findOrFail($id);

    // 2. Extra Safety: Prevent modifying other admins if needed
    // if ($user->role === 'admin' && auth()->user()->role !== 'super-admin') { ... }

    $user->status = $request->status;
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'User ' . ucfirst($request->status) . ' successfully!'
    ]);
}
}       