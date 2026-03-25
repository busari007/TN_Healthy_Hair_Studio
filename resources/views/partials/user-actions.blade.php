<div class="flex justify-center gap-2">
    @if($row->status === 'enabled')
        <button onclick="toggleUserStatus({{ $row->id }}, 'disabled')" 
                class="bg-red-500 hover:bg-red-600 text-white text-[10px] font-bold py-1 px-3 rounded-lg transition">
            Disable
        </button>
    @else
        <button onclick="toggleUserStatus({{ $row->id }}, 'enabled')" 
                class="bg-green-500 hover:bg-green-600 text-white text-[10px] font-bold py-1 px-3 rounded-lg transition">
            Enable
        </button>
    @endif
</div>
