<div class="flex items-center justify-center gap-2">
    @if($row->status === 'pending')
        {{-- Approve Button --}}
        <button 
            onclick="updateStatus({{ $row->id }}, 'approved')"
            class="Lato px-3 py-1 bg-green-600 hover:bg-green800 text-white text-[10px] font-black uppercase tracking-tighter rounded-lg transition-all transform hover:scale-105"
        >
            Approve
        </button>

        {{-- Reject Button --}}
        <button 
            onclick="updateStatus({{ $row->id }}, 'rejected')"
            class="Lato px-3 py-1 bg-red-600 hover:bg-red-800 text-white text-[10px] font-black uppercase tracking-tighter rounded-lg transition-all transform hover:scale-105"
        >
            Reject
        </button>
    @else
        {{-- Show 'No Actions' or a 'Cancel' button if already processed --}}
        <span class="text-[10px] font-bold text-gray-400 uppercase italic">Processed</span>
    @endif
</div>
