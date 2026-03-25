@extends('layouts.base')

@section('content')
{{-- Perfect Centering Wrapper --}}
<div class="min-h-screen bg-black flex flex-col items-center justify-center px-4 md:px-12">
    
    {{-- Header Container --}}
    <div class="w-full max-w-7xl mb-6 text-center">
        <h1 class="text-4xl font-bold text-white Playfair">Booking Records</h1>
        <p class="text-[#F0CCCE] Lato tracking-widest uppercase text-xs mt-2 italic">
            Studio Appointments Management
        </p>
    </div>

    {{-- The Table Card --}}
    <div class="bg-white rounded-3xl shadow-2xl p-6 md:p-10 w-full max-w-7xl">
        <div class="overflow-x-auto">
            <table id="bookings-table" class="display nowrap w-full text-left border-collapse" style="width:100%">
    <thead>
        <tr class="text-sm uppercase tracking-wider text-gray-500 border-b border-gray-100">
            <th class="pb-4 font-black">No</th>
            <th class="pb-4 font-black">Service</th>
            <th class="pb-4 font-black">Staff</th>
            <th class="pb-4 font-black text-center">Date</th>
            <th class="pb-4 font-black text-center">Time</th>
            <th class="pb-4 font-black text-center">Booked On</th>
            <th class="pb-4 font-black text-center">Status</th>
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                <th class="pb-4 font-black text-center">Actions</th>
            @endif
        </tr>
    </thead>
    <tbody class="text-gray-700 Lato"></tbody>
</table>

        </div>
    </div>
</div>

<style>
    /* Styling for Centered Controls */
    .dataTables_wrapper .dataTables_length, 
    .dataTables_wrapper .dataTables_filter {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 640px) {
        .dataTables_wrapper .dataTables_length, 
        .dataTables_wrapper .dataTables_filter {
            justify-content: center;
            flex-direction: column;
            gap: 10px;
        }
    }

    .dataTables_wrapper .dataTables_length select, 
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #F3F4F6;
        border-radius: 12px;
        padding: 8px 12px;
        outline: none;
        background: #F9FAFB;
    }

    .status-pill {
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        display: inline-block;
        white-space: nowrap;
    }
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        let isPrivileged = @json(auth()->user()->role === 'admin' || auth()->user()->role === 'staff');

        let columns = [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'service', name: 'service' },
            { data: 'staff', name: 'staff' },
            { data: 'date', name: 'date', className: 'text-center' },
            { data: 'time', name: 'time', className: 'text-center' },
            { 
                data: 'created_at', 
                name: 'created_at', 
                className: 'text-center text-xs text-gray-400',
                render: function(data) {
                    // Formats the date nicely in the browser
                    return new Date(data).toLocaleDateString('en-GB', {
                        day: '2-digit', month: 'short', year: 'numeric'
                    });
                }
            },
            { data: 'status', name: 'status', className: 'text-center' },
        ];

        if (isPrivileged) {
            columns.push({
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'text-center'
            });
        }

        $('#bookings-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('bookings') }}",
            // Sort by 'Booked On' (index 5) descending by default
            order: [[5, 'desc']], 
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search records...",
                lengthMenu: "Show _MENU_",
            },
            columns: columns
        });
    });

    function updateStatus(bookingId, status) {
        if (!confirm('Are you sure you want to ' + status + ' this booking?')) return;

        fetch(`/bookings/${bookingId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: status })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Reload the DataTable instantly
                $('#bookings-table').DataTable().ajax.reload(null, false);
            } else {
                alert(data.error || 'Update failed');
            }
        })
        .catch(err => alert('Error updating status'));
    }
</script>
@endpush
