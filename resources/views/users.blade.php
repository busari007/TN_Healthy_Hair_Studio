@extends('layouts.base') {{-- or layouts.base --}}

@section('content')
<div class="min-h-screen bg-black flex flex-col items-center py-12 px-4">
    <div class="w-full max-w-7xl mb-6 text-center">
        <h1 class="text-4xl font-bold text-white">User Management</h1>
        <p class="text-gray-400 uppercase text-xs tracking-widest mt-2">Manage Account Access</p>
    </div>

    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-7xl">
        <table id="users-table" class="display nowrap w-full text-left" style="width:100%">
            <thead>
                <tr class="text-sm uppercase tracking-wider text-gray-500 border-b">
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Joined</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700"></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('users.index') }}", // Ensure this route name matches web.php
        order: [[5, 'desc']],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'role', name: 'role' },
            { data: 'status', name: 'status', className: 'text-center' },
            { 
                data: 'created_at', 
                name: 'created_at', 
                className: 'text-center',
                render: data => new Date(data).toLocaleDateString('en-GB')
            },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ]
    });
});

function toggleUserStatus(userId, newStatus) {
    if (!confirm(`Are you sure you want to ${newStatus} this account?`)) return;

    fetch(`/users/${userId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            $('#users-table').DataTable().ajax.reload(null, false);
        } else {
            alert(data.error || 'Update failed');
        }
    });
}
</script>
@endpush
