@extends('layouts.base')

@section('content')
<div 
    id="booking-root"
    x-data="bookingApp()" 
    x-init="init()" 
    @date-selected.window="selectedDate = $event.detail; currentStep = 2"
    @staff-selected.window="selectedStaff = $event.detail.staff; currentStep = 3"
    @time-selected.window="
    selectedTime = $event.detail.time;
    submitBooking($event.detail)
"
    class="flex flex-col items-center min-h-screen bg-black px-4"
>

    <h1 class="Playfair text-3xl text-white mt-20">Book An Appointment</h1>

    <p class="text-gray-300 mt-2">
        Selected Service: 
        <span class="font-semibold" x-text="service.name"></span>
    </p>

    <!-- Steps -->
    <div class="flex gap-4 mt-4">
        <template x-for="step in [1,2,3]" :key="step">
            <button 
                @click="goToStep(step)"
                class="w-10 h-10 rounded-full border"
                :class="currentStep === step ? 'bg-white text-black' : 'text-gray-500'"
                x-text="step"
            ></button>
        </template>
    </div>

    <!-- STEP 1 -->
    <div x-show="currentStep === 1" class="mt-6 w-full">
        @include('components.services.calendar')
    </div>

    <!-- STEP 2 -->
    <div x-show="currentStep === 2" class="mt-6 w-full">
        @include('components.services.staff-availability')
    </div>

    <!-- STEP 3 -->
    <div x-show="currentStep === 3" class="mt-6 w-full">
        @include('components.services.time-selection')
    </div>

    <!-- Navigation -->
    <div class="flex gap-4 mt-8">
        <button @click="back()" x-show="currentStep > 1">Back</button>

        <button 
            @click="next()" 
            x-show="currentStep < 3"
            :disabled="!canProceed()"
        >
            Next
        </button>
    </div>

    <!-- Summary -->
    <div x-show="selectedTime" class="text-green-500 mt-6">
        <p>Booking Summary</p>
        <p x-text="'Service: ' + service.name"></p>
        <p x-text="'Date: ' + selectedDate?.day + '/' + selectedDate?.month + '/' + selectedDate?.year"></p>
        <p x-text="'Staff: ' + selectedStaff"></p>
        <p x-text="'Time: ' + selectedTime"></p>
    </div>

</div>

<script>
window.bookingApp = function () {
    return {
        service: {
            name: "{{ request('service_name') }}",
            amount: "{{ request('service_amount') }}"
        },

        selectedDate: null,
        selectedStaff: null,
        selectedTime: null,
        currentStep: 1,

        init() {
            if (!this.service.name) {
                window.location.href = "/#services";
            }
        },

        goToStep(step) {
            if (
                step === 1 ||
                (step === 2 && this.selectedDate) ||
                (step === 3 && this.selectedStaff)
            ) {
                this.currentStep = step;
            }
        },

        next() {
            if (this.currentStep === 1 && this.selectedDate) this.currentStep = 2;
            else if (this.currentStep === 2 && this.selectedStaff) this.currentStep = 3;
        },

        back() {
            if (this.currentStep > 1) this.currentStep--;
        },

        canProceed() {
            if (this.currentStep === 1) return this.selectedDate;
            if (this.currentStep === 2) return this.selectedStaff;
            return true;
        },

        submitBooking(detail) {
            const payload = {
                service: this.service.name,
                amount: this.service.amount,
                day: this.selectedDate.day,
                month: this.selectedDate.month,
                year: this.selectedDate.year,
                staff: this.selectedStaff,
                time: detail.time,
            };

            console.log('SUBMITTING:', payload); // debug

            fetch('/bookings/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) throw data;

                alert("Booking successful!");
                window.location.href = "/";
            })
            .catch(err => {
                console.error("Booking Error:", err);
                alert(err.error || "Something went wrong");
            });
        }
    }
}
</script>
@endsection