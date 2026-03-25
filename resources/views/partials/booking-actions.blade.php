<div class="flex items-center justify-center gap-2">
    @php $role = auth()->user()->role; @endphp

    {{-- Spinner SVG Template --}}
    @php 
        $spinner = '<svg class="animate-spin -ml-1 mr-2 h-3 w-3 text-white hidden inline-block" xmlns="http://www.w3.org" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    @endphp

    {{-- ADMIN ACTIONS --}}
    @if($role === 'admin')
        @if($row->status === 'pending' && !$row->is_refunded)
            <button onclick="updateStatus(this, {{ $row->id }}, 'approved')" class="flex items-center px-3 py-1 bg-green-600 hover:bg-green-800 text-white text-[10px] font-black uppercase rounded-lg transition-all transform hover:scale-105">
                {!! $spinner !!} <span>Approve</span>
            </button>
            <button onclick="updateStatus(this, {{ $row->id }}, 'rejected')" class="flex items-center px-3 py-1 bg-red-600 hover:bg-red-800 text-white text-[10px] font-black uppercase rounded-lg transition-all transform hover:scale-105">
                {!! $spinner !!} <span>Reject</span>
            </button>
        @endif

        {{-- Show Refund button only if NOT refunded AND NOT already rejected --}}
        @if(!$row->is_refunded && $row->status !== 'rejected')
            <button onclick="processRefund(this, {{ $row->id }})" class="flex items-center px-3 py-1 bg-orange-500 hover:bg-orange-700 text-white text-[10px] font-black uppercase rounded-lg transition-all transform hover:scale-105">
                {!! $spinner !!} <span>Refund</span>
            </button>
        @elseif($row->is_refunded)
            <span class="text-[10px] font-bold text-orange-600 uppercase italic">Refunded</span>
        @endif

    {{-- CLIENT ACTIONS --}}
    @elseif($role === 'user' || $role === 'client')
        {{-- Show Refund button only if NOT refunded AND NOT already rejected --}}
        @if(!$row->is_refunded && $row->status !== 'rejected')
             <button onclick="processRefund(this, {{ $row->id }})" class="flex items-center px-3 py-1 bg-orange-500 hover:bg-orange-700 text-white text-[10px] font-black uppercase rounded-lg transition-all transform hover:scale-105">
                {!! $spinner !!} <span>Refund</span>
             </button>
        @elseif($row->is_refunded)
            <span class="text-[10px] font-bold text-orange-600 uppercase italic text-center">Refund Processed</span>
        @endif

    {{-- STAFF ACTIONS --}}
    @elseif($role === 'staff')
        <span class="text-[10px] font-bold text-gray-400 uppercase italic">
            {{ $row->is_refunded ? 'Refunded' : 'No Actions' }}
        </span>
    @endif
</div>
