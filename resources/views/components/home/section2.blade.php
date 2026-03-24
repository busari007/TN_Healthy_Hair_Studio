{{-- ========================= --}}
{{-- SECTION 2: ABOUT US --}}
{{-- ========================= --}}
<div class="w-full">
    <div class="h-full flex flex-col md:flex-row items-center justify-center m-4 md:m-10 lg:mt-20 lg:mb-20 gap-8 lg:gap-16">

        {{-- Text --}}
        <div class="flex flex-col items-center md:items-start text-center md:text-left lg:w-1/2">
            <h1 class="Playfair text-[28px] lg:text-[45px] mt-10 leading-9 lg:leading-[55px]">
                Embrace Your Inner Peace <br/> and Discover True Beauty
            </h1> 

            <p class="Lato text-xs lg:text-lg mt-6 max-w-[85%]">
                At TN Healthy Hair Studio, we believe beauty grows from calm.
                Each treatment is thoughtfully designed to relax your mind 
                and care for your hair with gentle attention.
            </p>
        </div>

        {{-- Image --}}
        <div class="w-[90%] lg:w-1/2 flex justify-center">
            <img 
                src="{{ asset('images/section2_spa_image.webp') }}"
                class="w-full rounded-xl shadow-lg"
            >
        </div>

    </div>
</div>