{{-- ========================= --}}
{{-- SECTION 7: CONTACT --}}
{{-- ========================= --}}
<div 
    id="contact"
    x-data="{
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        message: '',
        submitForm() {
            const whatsappMessage = `Hello, My name is ${this.firstName} ${this.lastName}%0A, my email address is ${this.email}%0A and I want to make an Inquiry saying: ${this.message}`;
            const url = `https://wa.me/2347035421098?text=${whatsappMessage}`;
            window.open(url, '_blank');
        }
    }"
    class="w-full bg-black lg:flex justify-center"
>

    <div class="w-full lg:w-[80%] text-white flex flex-col lg:flex-row justify-between items-center py-20 px-6 lg:px-24 gap-12">

        {{-- Header --}}
        <div class="lg:w-1/2 text-center lg:text-left">
            <h1 class="Playfair text-4xl md:text-5xl font-semibold mb-4">
                Make an inquiry
            </h1>

            <p class="Lato text-sm md:text-base text-gray-300 max-w-md mx-auto lg:mx-0">
                We’d love to hear from you. Send us a quick message and our team will get back to you shortly.
            </p>
        </div>

        {{-- Form --}}
        <form 
            @submit.prevent="submitForm"
            class="lg:w-1/2 w-full flex flex-col gap-4 max-w-lg"
        >

            {{-- Name --}}
            <div class="flex flex-col md:flex-row gap-4">
                <input type="text" x-model="firstName" placeholder="First Name" required
                    class="Lato w-full bg-transparent border border-gray-500 rounded-md px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-[#F0CCCE]" />

                <input type="text" x-model="lastName" placeholder="Last Name" required
                    class="Lato w-full bg-transparent border border-gray-500 rounded-md px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-[#F0CCCE]" />
            </div>

            {{-- Email --}}
            <input type="email" x-model="email" placeholder="Email Address" required
                class="Lato w-full bg-transparent border border-gray-500 rounded-md px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-[#F0CCCE]" />

            {{-- Phone --}}
            <input type="tel" x-model="phone" placeholder="+234 ### ### ####" required
                class="Lato w-full bg-transparent border border-gray-500 rounded-md px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-[#F0CCCE]" />

            {{-- Message --}}
            <textarea x-model="message" rows="5" placeholder="Message" required
                class="Lato w-full bg-transparent border border-gray-500 rounded-md px-4 py-3 text-white placeholder-gray-400 resize-none focus:outline-none focus:border-[#F0CCCE]"></textarea>

            {{-- Submit --}}
            <button type="submit"
                class="Jakarta mt-4 bg-[#F0CCCE] text-black font-semibold rounded-md py-3 hover:bg-[#e8b9bc] transition">
                Send Message
            </button>

        </form>
    </div>
</div>