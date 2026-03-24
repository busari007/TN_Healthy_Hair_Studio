<div x-data="calendarComponent()" x-init="init()" class="max-w-md mx-auto bg-white p-6 rounded-2xl shadow-xl mt-10 border border-gray-100">

    <!-- Loading State -->
    <div x-show="loading" class="flex flex-col items-center justify-center py-10">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-pink-500 mb-2"></div>
        <p class="text-gray-500 text-sm Lato">Checking availability...</p>
    </div>

    <div x-show="!loading" x-cloak>
        
        <!-- Calendar Header -->
        <div class="flex justify-between items-center mb-6">
            <button @click="changeMonth(-1)" class="p-2 hover:bg-pink-50 rounded-full transition-colors text-pink-600 font-bold">
                <svg xmlns="http://www.w3.org" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            
            <h2 class="text-xl font-bold text-gray-800 Playfair" x-text="monthYear"></h2>
            
            <button @click="changeMonth(1)" class="p-2 hover:bg-pink-50 rounded-full transition-colors text-pink-600 font-bold">
                <svg xmlns="http://www.w3.org" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <!-- Calendar Grid -->
        <div class="grid grid-cols-7 gap-2">
            <!-- Day Headers -->
            <template x-for="day in daysOfWeek">
                <div class="text-xs font-bold uppercase tracking-widest text-gray-400 text-center mb-2" x-text="day"></div>
            </template>

            <!-- Empty slots for previous month -->
            <template x-for="blank in blanks">
                <div class="aspect-square"></div>
            </template>

            <!-- Actual Days -->
            <template x-for="date in days">
                <div 
                    @click="selectDate(date)"
                    x-text="date.getDate()"
                    class="aspect-square flex items-center justify-center text-sm font-semibold rounded-lg transition-all duration-200 cursor-pointer border"
                    :class="getClass(date)"
                ></div>
            </template>
        </div>

        <!-- Legend -->
        <div class="mt-6 flex justify-center gap-4 text-xs text-gray-500 Lato">
            <div class="flex items-center gap-1">
                <span class="w-3 h-3 bg-[#F0CCCE] rounded-sm"></span> Available
            </div>
            <div class="flex items-center gap-1">
                <span class="w-3 h-3 bg-gray-100 border border-gray-200 rounded-sm"></span> Booked/Past
            </div>
        </div>
    </div>
</div>


<script>
function calendarComponent() {
    return {
        currentMonth: new Date(),
        days: [],
        blanks: [],
        bookedDates: [],
        loading: true,
        daysOfWeek: ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],

        get monthYear() {
            return this.currentMonth.toLocaleString('default', {
                month: 'long',
                year: 'numeric'
            });
        },

        init() {
            this.generateCalendar();
            this.fetchBookedDates();
        },

        generateCalendar() {
            let year = this.currentMonth.getFullYear();
            let month = this.currentMonth.getMonth();

            let firstDay = new Date(year, month, 1).getDay();
            let lastDate = new Date(year, month + 1, 0).getDate();

            this.blanks = Array(firstDay).fill(null);
            this.days = [];

            for (let i = 1; i <= lastDate; i++) {
                this.days.push(new Date(year, month, i));
            }
        },

        async fetchBookedDates() {
    try {
        let res = await fetch('/api/bookings/booked-dates');

        // console.log('STATUS:', res.status);
        let data = await res.json();

        // console.log('DATA:', data);

        this.bookedDates = data.bookedDates || [];
    } catch (e) {
        console.error('FETCH ERROR:', e);
    } finally {
        this.loading = false;
    }
},

        changeMonth(offset) {
    this.currentMonth = new Date(
        this.currentMonth.getFullYear(),
        this.currentMonth.getMonth() + offset,
        1
    );

    this.generateCalendar();
    this.fetchBookedDates(); // ✅ ADD THIS
},

        isBooked(date) {
            return this.bookedDates.some(b =>
                b.day === date.getDate() &&
                b.month === date.getMonth() + 1 &&
                b.year === date.getFullYear()
            );
        },

        selectDate(date) {
    if (this.isBooked(date)) return;

    // send selected date to parent
    this.$dispatch('date-selected', {
        day: date.getDate(),
        month: date.getMonth() + 1,
        year: date.getFullYear()
    });
},

getClass(date) {
    const today = new Date()
    today.setHours(0,0,0,0)
    if (this.isBooked(date) || date < today) return 'bg-gray-300 cursor-not-allowed'
    return 'bg-pink-200 hover:bg-pink-300'
}
    }
}
</script>