<div x-data="staffAvailability()" x-init="init()" class="flex flex-col items-center w-full min-h-[400px]">

    <!-- Header with dynamic date -->
    <h2 class="text-2xl font-bold mb-8 text-center text-white Playfair tracking-wide">
        Select a Staff for 
        <span class="text-[#F0CCCE]" x-text="dateText"></span>
    </h2>

    <!-- Professional Loading State -->
    <div x-show="loading" class="flex flex-col items-center justify-center py-20" x-transition>
        <div class="animate-spin rounded-full h-14 w-14 border-t-4 border-b-4 border-[#F0CCCE] mb-6"></div>
        <p class="text-gray-400 Lato animate-pulse tracking-widest uppercase text-xs">
            Syncing staff schedules...
        </p>
    </div>

    <!-- Staff Grid (Visible only after loading) -->
    <div x-show="!loading" x-cloak x-transition.opacity.duration.500ms 
         class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-5xl px-6">
        
        <template x-for="staff in staffList" :key="staff">
            <button
                @click="selectStaff(staff)"
                :disabled="isFullyBooked(staff)"
                class="group relative p-10 rounded-3xl border-2 transition-all duration-500 flex flex-col items-center gap-4 overflow-hidden shadow-2xl"
                :class="buttonClass(staff)"
            >
                <!-- Initial Circle Decoration -->
                <div class="w-20 h-20 bg-black/5 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-500">
                    <span class="text-3xl font-serif opacity-50" x-text="staff.charAt(0)"></span>
                </div>

                <span x-text="staff" class="Playfair text-xl tracking-tight"></span>

                <!-- Status Badge -->
                <div class="px-4 py-1 rounded-full text-[10px] uppercase tracking-tighter font-black bg-white/20" 
                     x-text="availabilityText(staff)">
                </div>
            </button>
        </template>
    </div>
</div>

<script>
window.staffAvailability = function () {
    return {
        staffList: ["Mrs Ebun", "Stephanie", "Ayomide"],
        availability: {},
        loading: true,
        selectedStaff: null,

        // 1. Helper to find the parent #booking-root safely
        getParent() {
            const el = document.getElementById('booking-root');
            return el ? Alpine.$data(el) : null;
        },

        // 2. Format the date for the UI
        get dateText() {
            const parent = this.getParent();
            if (!parent || !parent.selectedDate) return 'No date selected';

            const d = new Date(parent.selectedDate.year, parent.selectedDate.month - 1, parent.selectedDate.day);
            return d.toLocaleDateString('en-US', {
                weekday: 'long',
                day: 'numeric',
                month: 'short'
            });
        },

        init() {
            // Watch for date changes in the parent
            this.$watch(() => {
                const p = this.getParent();
                return p ? JSON.stringify(p.selectedDate) : null;
            }, (value) => {
                if (value) this.fetchAvailability();
            });

            // Initial fetch if we arrived at this step with a date already set
            if (this.getParent()?.selectedDate) {
                this.fetchAvailability();
            }
        },

        async fetchAvailability() {
            const parent = this.getParent();
            if (!parent || !parent.selectedDate) return;

            const date = parent.selectedDate;
            this.loading = true;

            try {
                // OPTIMIZATION: Fetch all staff at the same time (Parallel)
                const fetchPromises = this.staffList.map(name => 
                    fetch(`/api/bookings/check-staff-availability?staff=${encodeURIComponent(name)}&day=${date.day}&month=${date.month}&year=${date.year}`)
                    .then(res => res.json())
                    .then(data => ({
                        name, 
                        times: (data.bookedTimes || []).map(t => t.replace(/\s+/g, '').toUpperCase())
                    }))
                );

                const rawResults = await Promise.all(fetchPromises);
                
                // Map the array results back to our availability object
                let newAvailability = {};
                rawResults.forEach(item => {
                    newAvailability[item.name] = item.times;
                });

                this.availability = newAvailability;
                // console.log('SYNCED STAFF DATA:', this.availability);

            } catch (e) {
                console.error("Staff Availability Error:", e);
            } finally {
                // Artificial 300ms delay to prevent "flicker" on fast connections
                setTimeout(() => { this.loading = false; }, 300);
            }
        },

        isFullyBooked(staff) {
            const booked = this.availability[staff] || [];
            return booked.includes("9:00AM") && booked.includes("12:00PM");
        },

        availabilityText(staff) {
            const booked = this.availability[staff] || [];
            if (this.isFullyBooked(staff)) return "Fully booked";
            if (booked.length === 0) return "All slots free";
            if (booked.includes("9:00AM")) return "12:00PM Available";
            if (booked.includes("12:00PM")) return "9:00AM Available";
            return "Available";
        },

        selectStaff(staff) {
            if (this.isFullyBooked(staff)) return;
            this.selectedStaff = staff;
            
            // Send selection to the parent 'bookingApp'
            this.$dispatch('staff-selected', { staff: staff });
        },

        buttonClass(staff) {
            if (this.isFullyBooked(staff)) {
                return "bg-gray-800/50 text-gray-500 border-gray-700 cursor-not-allowed grayscale";
            }

            // Default State
            let base = "bg-[#F0CCCE] text-black border-transparent hover:shadow-[0_0_20px_rgba(240,204,206,0.3)] hover:scale-[1.02] cursor-pointer";

            // Selected State
            if (this.selectedStaff === staff) {
                base = "bg-white text-black border-[#F0CCCE] ring-4 ring-[#F0CCCE]/40 scale-105 shadow-2xl";
            }

            return base;
        }
    }
};
</script>

<style>
    /* Ensure the grid doesn't pop in too harshly */
    [x-cloak] { display: none !important; }
</style>
