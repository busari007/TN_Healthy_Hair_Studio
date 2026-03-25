{{-- ========================= --}}
{{-- SECTION 3: SERVICES --}}
{{-- ========================= --}}
<div 
    x-data="servicesComponent()"
    class="w-full bg-[#222222] text-white pb-24"
>

    {{-- Header --}}
    <div id="services" class="text-center px-4 lg:px-20">
        <h1 class="Playfair text-3xl lg:text-5xl mt-12 lg:mt-24">
            Our Services
        </h1>
        <p class="Lato text-sm max-w-[85%] mx-auto mt-4">
            Each treatment is crafted to restore balance and reveal your natural glow.
        </p>
    </div>

    {{-- Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 p-5 mt-10">
        <template x-for="service in services" :key="service.name">
            <div 
                @click="openModal(service)"
                class="bg-white text-black rounded-2xl shadow-lg hover:-translate-y-2 transition cursor-pointer"
            >

                <div class="relative">
                    <img :src="service.image" class="h-[250px] w-full object-cover rounded-t-2xl">
                    <h1 class="absolute bottom-3 left-4 text-white Playfair text-lg"
                        x-text="service.name"></h1>
                </div>

                <div class="p-4">
                    <h2 class="font-bold" x-text="service.time"></h2>
                    <p class="text-sm mt-2" x-text="service.description"></p>
                </div>

            </div>
        </template>
    </div>

{{-- MODAL --}}
<div 
    x-show="showModal"
    x-transition
    x-transition.opacity
    x-cloak
    @click="closeModal()"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
>
    <div 
        @click.stop
        class="bg-white text-black w-[90%] md:w-[500px] rounded-xl p-5 max-h-[90vh] overflow-y-auto"
    >

        <h2 class="text-xl font-bold text-center mb-3 text-black"
            x-text="selectedService.name"></h2>

        <img :src="selectedService.image"
            class="w-full h-48 object-cover rounded mb-3">

        <p class="text-sm mb-4 text-gray-700"
           x-text="selectedService.description"></p>

        {{-- Includes --}}
        <h3 class="font-semibold mb-2 text-black">What's Included</h3>
        <ul class="list-disc pl-5 text-sm mb-4 text-gray-700">
            <template x-for="item in selectedService.includes" :key="item">
                <li x-text="item"></li>
            </template>
        </ul>

        {{-- Price --}}
        <p class="font-bold mb-1 text-black">
            Booking Fee: ₦<span x-text="selectedService.amount"></span>
        </p>
        <p class="text-sm text-gray-500 mb-4"
           x-text="selectedService.time"></p>

        {{-- Policy --}}
        <div class="border-t pt-3 mt-4">
            <h3 class="font-semibold mb-2 text-center text-black">Policy</h3>
            <ul class="list-disc pl-5 text-xs space-y-1 text-gray-600">
                <template x-for="note in selectedService.policyNotes" :key="note">
                    <li x-text="note"></li>
                </template>
            </ul>

            <p class="text-xs mt-3 italic text-center text-gray-500"
               x-text="selectedService.priceNote"></p>
        </div>

        {{-- BOOK BUTTON --}}
<a 
    :href="`/book-a-service?service_name=${encodeURIComponent(selectedService.name)}&service_amount=${selectedService.amount}`"
    class="block mt-6 w-full text-center bg-[#F0CCCE] py-2 rounded-xl text-black font-semibold"
>
    Book Now
</a>

    </div>
</div>
</div>


{{-- ========================= --}}
{{-- Alpine Component --}}
{{-- ========================= --}}
<script>
function servicesComponent() {
    return {
        showModal: false,
        selectedService: {},
        services: [
            {
                name: 'Moisture Fusion',
                image: '/images/Moisture_Fusion.webp',
                amount: 10000,
                time: '60 - 90 Minutes',
                description: 'Revive and hydrate your hair...',
                includes: [
                    'Hot Oil Treatment',
                    'Micromist Shampoo System',
                    '3-step Shampoo System',
                    'Deep Conditioning',
                    'Split Ends Trimming',
                    'Protective Style'
                ],
                policyNotes: [
                    '₦10,000 penalty for late arrival over 10 min',
                    'Booking deposit is non-refundable',
                    'Cancellation without 12 hr notice = ₦10,000 fee'
                ],
                priceNote: 'Extra charges may apply'
            },
            {
                name: 'Strengthening / Bond Repair',
                image: '/images/Protein Strength Treatment.webp',
                amount: 10000,
                time: '60 Minutes',
                description: 'Restore your hair strength...',
                includes: [
                    'Protein Treatment',
                    'Deep Conditioning',
                    'Protective Styling'
                ],
                policyNotes: [
                    '₦10,000 penalty for late arrival over 10 min',
                    'Booking deposit is non-refundable',
                    'Cancellation without 12 hr notice = ₦10,000 fee'
                ],
                priceNote: 'Extra charges may apply'
            },
            {
                name: 'Hair Restoration',
                image: '/images/New Client Hair Repair Hair Restoration.webp',
                amount: 10000,
                time: '30 - 40 Minutes',
                description: 'Begin your healthy hair journey...',
                includes: [
                    'Consultation',
                    'Scalp Analysis',
                    'Treatment Plan'
                ],
                policyNotes: [
                    '₦10,000 penalty for late arrival over 10 min',
                    'Booking deposit is non-refundable',
                    'Cancellation without 12 hr notice = ₦10,000 fee'
                ],
                priceNote: 'Consultation fee deducted later'
            },
            {
                name: 'DIY Hair Care',
                image: '/images/Do It Yourself (DIY) Hair Care.webp',
                amount: 10000,
                time: '30 - 40 Minutes',
                description: 'Take control of your hair care...',
                includes: [
                    'Routine Plan',
                    'Product Guidance',
                    'Demo'
                ],
                policyNotes: [
                    '₦10,000 penalty for late arrival over 10 min',
                    'Booking deposit is non-refundable',
                    'Cancellation without 12 hr notice = ₦10,000 fee'
                ],
                priceNote: 'Customized to your needs'
            },
            {
                name: 'Relaxer',
                image: '/images/Chemical Process (Relaxer).webp',
                amount: 10000,
                time: '60 Minutes',
                description: 'Professional relaxing service...',
                includes: [
                    'Application',
                    'Conditioning',
                    'Styling'
                ],
                policyNotes: [
                    '₦10,000 penalty for late arrival over 10 min',
                    'Booking deposit is non-refundable',
                    'Cancellation without 12 hr notice = ₦10,000 fee'
                ],
                priceNote: 'Prep not included'
            },
            {
                name: 'Dandruff Treatment',
                image: '/images/Dandruff.webp',
                amount: 10000,
                time: '30 - 40 Minutes',
                description: 'Say goodbye to flakes...',
                includes: [
                    'Scalp Detox',
                    'Treatment',
                    'Conditioning'
                ],
                policyNotes: [
                    '₦10,000 penalty for late arrival over 10 min',
                    'Booking deposit is non-refundable',
                    'Cancellation without 12 hr notice = ₦10,000 fee'
                ],
                priceNote: 'Best with weekly sessions'
            }
        ],

        openModal(service) {
            this.selectedService = service;
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        }
    }
}
</script>