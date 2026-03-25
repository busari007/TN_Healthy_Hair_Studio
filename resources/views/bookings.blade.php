@extends('layouts.base')

@section('content')
<div class="min-h-screen bg-black flex flex-col items-center justify-center px-4 md:px-12">
    
    <div class="w-full max-w-7xl mb-6 text-center">
        <h1 class="text-4xl font-bold text-white Playfair">Booking Records</h1>
        <p class="text-[#F0CCCE] Lato tracking-widest uppercase text-xs mt-2 italic">
            Studio Appointments Management
        </p>
    </div>

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
                        <th class="pb-4 font-black text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 Lato"></tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .dataTables_wrapper .dataTables_length, 
    .dataTables_wrapper .dataTables_filter {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .dataTables_wrapper .dataTables_length select, 
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #F3F4F6;
        border-radius: 12px;
        padding: 8px 12px;
        outline: none;
        background: #F9FAFB;
    }
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let columns = [
    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
    { data: 'service', name: 'service' },
    { data: 'staff', name: 'staff' },
    { data: 'date', name: 'date', className: 'text-center' },
    { data: 'time', name: 'time', className: 'time', className: 'text-center' },
    { data: 'created_at', name: 'created_at', className: 'text-center' },
    { data: 'status', name: 'status', orderable: false, searchable: false, className: 'text-center' },
    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
];

        $('#bookings-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('bookings') }}",
            order: [[5, 'desc']], 
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search records...",
                lengthMenu: "Show _MENU_",
            },
            columns: columns
        });
    });

 function toggleLoading(btn, isLoading) {
    const spinner = btn.querySelector('svg');
    const span = btn.querySelector('span');
    
    if (isLoading) {
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        spinner.classList.remove('hidden');
        if(span) span.innerText = 'Processing...';
    } else {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
        spinner.classList.add('hidden');
        // Original text will be restored when DataTable reloads, 
        // but for errors, we manualy reset:
        if(span) span.innerText = btn.getAttribute('data-original-text');
    }
}

function processRefund(btn, bookingId) {
    if (!confirm('Are you sure you want to refund this payment?')) return;
    
    toggleLoading(btn, true);

    fetch(`/bookings/${bookingId}/refund`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            $('#bookings-table').DataTable().ajax.reload(null, false);
        } else {
            alert(data.error || 'Refund failed');
            toggleLoading(btn, false);
        }
    })
    .catch(err => {
        alert('Error processing refund');
        toggleLoading(btn, false);
    });
}

function updateStatus(btn, bookingId, status) {
    if (!confirm('Are you sure you want to ' + status + ' this booking?')) return;

    toggleLoading(btn, true);

    fetch(`/bookings/${bookingId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            $('#bookings-table').DataTable().ajax.reload(null, false);
        } else {
            alert(data.error || 'Update failed');
            toggleLoading(btn, false);
        }
    })
    .catch(err => {
        alert('Error updating status');
        toggleLoading(btn, false);
    });
}
</script>
@endpush
