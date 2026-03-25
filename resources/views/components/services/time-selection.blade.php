<div x-data="timeSelection()" x-init="init()" class="flex flex-col items-center w-full min-h-[300px]">

    <h2 class="text-2xl font-bold mb-8 text-center text-white Playfair tracking-wide">
        Select a Time for <span class="text-[#F0CCCE]" x-text="getParent()?.selectedStaff"></span>
    </h2>

    <!-- Professional Loading State -->
    <div x-show="loading" class="flex flex-col items-center justify-center py-16" x-transition>
        <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-b-4 border-[#F0CCCE] mb-4"></div>
        <p class="text-gray-400 Lato animate-pulse tracking-widest uppercase text-[10px]">
            Checking Available Slots...
        </p>
    </div>

    <!-- Time Slots Grid -->
    <div x-show="!loading" x-cloak x-transition.opacity.duration.500ms 
         class="flex flex-wrap justify-center gap-6 w-full max-w-2xl px-4">
        
        <template x-for="(time, index) in timeSlots" :key="index">
            <button
                @click="selectTime(time)"
                :disabled="isBooked(time)"
                class="group relative px-10 py-6 rounded-2xl border-2 transition-all duration-300 flex flex-col items-center shadow-xl min-w-[160px]"
                :class="buttonClass(time)"
            >
                <span class="text-xl font-bold tracking-tighter" x-text="time"></span>
                
                <span class="text-[10px] uppercase font-black opacity-60 mt-1" 
                      x-text="isBooked(time) ? 'Reserved' : 'Available'"></span>
            </button>
        </template>
    </div>
</div>

<script>
window.timeSelection = function () {
    return {
        bookedTimes: [],
        timeSlots: ["9:00AM", "12:00PM"],
        loading: true,
        selectedTime: null,

        // 1. Safe Parent Access
        getParent() {
            const el = document.getElementById('booking-root');
            return el ? Alpine.$data(el) : null;
        },

        init() {
            // Watch Parent Staff
            this.$watch(() => {
                const p = this.getParent();
                return p ? p.selectedStaff : null;
            }, () => this.fetchTimes());

            // Watch Parent Date (Stringify to catch object internal changes)
            this.$watch(() => {
                const p = this.getParent();
                return p ? JSON.stringify(p.selectedDate) : null;
            }, () => this.fetchTimes());

            // Run initial fetch
            this.fetchTimes();
        },

        async fetchTimes() {
            const parent = this.getParent();
            if (!parent || !parent.selectedDate || !parent.selectedStaff) return;

            const date = parent.selectedDate;
            const staff = parent.selectedStaff;

            this.loading = true;

            try {
                let res = await fetch(
                    `/api/bookings/booked-times?staff=${encodeURIComponent(staff)}&day=${date.day}&month=${date.month}&year=${date.year}`
                );
                
                let data = await res.json();
                
                // Sync and Normalize (Upper, No Spaces) to match frontend slots
                this.bookedTimes = (data.bookedTimes || []).map(t => t.replace(/\s+/g, '').toUpperCase());
                
                // console.log('SYNCED SLOTS:', this.bookedTimes);

            } catch (e) {
                console.error("Time Fetch Error:", e);
            } finally {
                // Smooth transition
                setTimeout(() => { this.loading = false; }, 400);
            }
        },

        isBooked(time) {
            const clean = (t) => t.replace(/\s+/g, '').toUpperCase();
            return this.bookedTimes.some(booked => clean(booked) === clean(time));
        },

 selectTime(time) {
    if (this.isBooked(time)) return;

    this.selectedTime = time;

    const parent = this.getParent();

    const bookingData = {
        // ✅ Change these to match your bookingApp structure
        service: parent.service.name,    
        amount: parent.service.amount,   
        staff: parent.selectedStaff,
        day: parent.selectedDate.day,
        month: parent.selectedDate.month,
        year: parent.selectedDate.year,
        time: time,
        email: "{{ auth()->user()->email ?? '' }}" 
    };

    console.log("SUCCESSFUL DATA:", bookingData);

    // ✅ Store for payment page
    sessionStorage.setItem("pendingBooking", JSON.stringify(bookingData));

    // ✅ Now you can uncomment the redirect
    window.location.href = "/payment";
},

        buttonClass(time) {
            if (this.isBooked(time)) {
                return "bg-gray-800/40 text-gray-500 border-gray-700 cursor-not-allowed grayscale";
            }

            // Available State
            let base = "bg-[#F0CCCE] text-black border-transparent hover:scale-105 hover:shadow-pink-900/20 cursor-pointer active:scale-95";

            // Selected State
            if (this.selectedTime === time) {
                base = "bg-white text-black border-[#F0CCCE] ring-4 ring-[#F0CCCE]/50 scale-110 z-10 shadow-2xl";
            }

            return base;
        }
    }
};
</script>
