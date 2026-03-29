{{-- ========================= --}}
{{-- SECTION 1: LANDING HERO --}}
{{-- ========================= --}}
<div 
    x-data="{
        bg: window.innerWidth >= 1024 
            ? '{{ asset('images/Spa_Landing_Image_Large.webp') }}' 
            : '{{ asset('images/Spa_Landing_Image_Mobile.webp') }}',
        updateBg() {
            this.bg = window.innerWidth >= 1024 
                ? '{{ asset('images/Spa_Landing_Image_Large.webp') }}' 
                : '{{ asset('images/Spa_Landing_Image_Mobile.webp') }}'
        }
    }"
    x-init="window.addEventListener('resize', updateBg)"
    :style="'background-image: url(' + bg + ')'"
    class="relative flex items-center justify-center w-full h-screen bg-cover bg-center bg-no-repeat transition-all duration-300"
>

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/30 z-10"></div>

    {{-- Content --}}
    <div class="relative z-20 flex flex-col items-center justify-center text-white text-center mt-20 lg:mt-40">
        
        <h1 class="Playfair text-4xl lg:text-5xl xl:text-6xl leading-tight lg:leading-[1.20] drop-shadow-md">
            Your Journey To Healthy Hair<br/>
            Starts Here 
        </h1>

        <p class="Lato text-sm md:text-base xl:text-xl mt-5 max-w-[70%] lg:max-w-[50%] leading-relaxed opacity-95">
            Learn the science needed to achieve waist length hair for black women
        </p>

        <a href="#services" class="z-30 mt-12 md:mt-16">
            <div class="flex items-center gap-2 px-4 py-2 md:px-5 md:py-2 bg-[#F0CCCE] rounded-2xl hover:bg-[#e8b9bc] transition shadow-lg">
                <p class="Jakarta text-[11px] md:text-base font-semibold text-gray-800">
                    VIEW ALL TREATMENTS
                </p>
                <div class="bg-white rounded-full p-2">
                    <img src="https://cdn-icons-png.flaticon.com/128/545/545682.png" class="w-4 h-4">
                </div>
            </div>
        </a>

    </div>
</div>