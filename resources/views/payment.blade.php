@extends('layouts.base')

@section('content')
<div 
    x-data="{ 
        booking: {}, 
        loaded: false,
        async pay() {
            try {
                const res = await fetch('/api/payments/init', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.booking)
                });

                const result = await res.json();
                console.log('Paystack Response:', result);

                if (result.status && result.data && result.data.authorization_url) {
                    sessionStorage.setItem('payment_reference', result.data.reference);
                    window.location.href = result.data.authorization_url;
                } else {
                    alert('Payment initialization failed: ' + (result.message || 'Unknown error'));
                }

            } catch (e) {
                console.error('Fetch Error:', e);
                alert('Could not connect to the payment server.');
            }
        } // ✅ This was missing!
    }" 
    x-init="
        const data = sessionStorage.getItem('pendingBooking');
        if (data) {
            booking = JSON.parse(data);
            loaded = true;
        }
    "
    class="min-h-screen flex items-center justify-center bg-gray-100"
>
    <div x-show="loaded" x-cloak class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md text-center">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">Checkout</h2>

        <div class="bg-gray-50 rounded-xl p-6 mb-6 text-left">
            <p class="text-xs uppercase tracking-widest text-gray-500 font-semibold">Service</p>
            <p class="font-bold text-xl text-gray-800 mb-4" x-text="booking.service"></p>

            <p class="text-xs uppercase tracking-widest text-gray-500 font-semibold">Scheduled For</p>
            <p class="text-gray-700 font-medium">
                <span x-text="booking.day"></span>/<span x-text="booking.month"></span>/<span x-text="booking.year"></span> 
                at <span x-text="booking.time"></span>
            </p>
        </div>

        <div class="mb-8">
            <p class="text-gray-500 text-sm mb-1">Total Amount</p>
            <p class="text-4xl font-black text-gray-900">
                ₦<span x-text="Number(booking.amount).toLocaleString()"></span>
            </p>
        </div>

        <button 
            @click="pay()"
            class="w-full bg-black hover:bg-gray-800 text-white py-4 rounded-xl font-bold text-lg transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg"
        >
            Pay with Paystack
        </button>
        
        <p class="mt-4 text-xs text-gray-400">Secure Payment via Paystack</p>
    </div>
</div>
@endsection
