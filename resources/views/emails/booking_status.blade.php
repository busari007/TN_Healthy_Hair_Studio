<!DOCTYPE html>
<html>
<head>
    <style>
        .wrapper { background-color: #f9fafb; padding: 40px 20px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        .card { max-width: 500px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; }
        .header { background-color: #000000; padding: 30px; text-align: center; }
        .logo { max-width: 150px; height: auto; }
        .content { padding: 30px; color: #374151; }
        .title { font-size: 20px; font-weight: bold; color: #111827; margin-bottom: 20px; text-align: center; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px id #f3f4f6; }
        .label { font-weight: 600; color: #6b7280; font-size: 13px; text-transform: uppercase; }
        .value { font-weight: 500; color: #111827; font-size: 14px; }
        .footer { padding: 20px; text-align: center; background: #f9fafb; border-top: 1px solid #e5e7eb; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #000000; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px; margin-top: 10px; }
        .amount-tag { color: #b91c1c; font-weight: 800; font-size: 18px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <!-- Header with Logo -->
            <div class="header">
                {{-- Use asset() or a direct URL if hosted --}}
                <img src="{{ $message->embed(public_path('images/TN-Skincare logo.webp')) }}" alt="TN Healthy Hair Studio" class="logo">
            </div>

            <div class="content">
    <!-- Updated Title: Shows "Refunded" instead of "Rejected" for better customer experience -->
    <div class="title">
        @if($booking->status === 'rejected')
            Your Booking for {{ $booking->service }} was Refunded
        @else
            Booking For {{ $booking->service }} On {{ $booking->time }} Has Been {{ ucfirst($booking->status) }}
        @endif
    </div>
    
    <table width="100%" cellpadding="10" cellspacing="0">
        <tr>
            <td class="label">Service</td>
            <td class="value" align="right">{{ $booking->service }}</td>
        </tr>
        <tr>
            <td class="label">Date/Time</td>
            <td class="value" align="right">{{ $booking->day }}/{{ $booking->month }}/{{ $booking->year }} @ {{ $booking->time }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td class="value" align="right">
                <!-- Change the text display for the user -->
                {{ $booking->status === 'rejected' ? 'Refunded' : ucfirst($booking->status) }}
            </td>
        </tr>
        <tr>
            <td class="label">Amount</td>
            <td class="amount-tag" align="right">₦{{ number_format($booking->amount) }}</td>
        </tr>
    </table>

    <!-- New Refund Message -->
    @if($booking->status === 'rejected')
        <div style="margin-top: 20px; padding: 15px; background-color: #fff7ed; border: 1px solid #ffedd5; border-radius: 8px; color: #9a3412; font-size: 13px; text-align: center;">
            <strong>Refund Processed:</strong> Since your booking was not approved, we have automatically reversed your payment. Please allow 3-5 business days for it to appear in your account.
        </div>
    @endif
</div>

<div class="footer">
    <p style="font-size: 12px; color: #9ca3af;">
        {{ $booking->status === 'rejected' ? 'Your booking has been cancelled and refunded.' : 'Please log in to your dashboard to manage this request.' }}
    </p>
    <a href="{{ url('/signin') }}" class="btn">Open Dashboard</a>
</div>

        </div>
    </div>
</body>
</html>
