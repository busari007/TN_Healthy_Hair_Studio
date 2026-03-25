<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

    class Booking extends Model
{
    protected $fillable = [
        'service',
        'amount',
        'day',
        'month',
        'year',
        'staff',
        'time',
        'name',
        'user_id',
        'email',
        'status',
        'reminder_sent',
        'payment_reference',
        'refund_id',
        'is_refunded'
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
}
