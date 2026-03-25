<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function updateStatus($id, Request $request)
{
    $request->validate([
        'status' => 'required|in:enabled,disabled'
    ]);

    $user = \App\Models\User::findOrFail($id);

    $user->status = $request->status;
    $user->save();


    return response()->json([
        'success' => true,
        'message' => 'User ' . ucfirst($request->status) . ' successfully!'
    ]);
}
}
