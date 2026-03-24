{{-- ========================= --}}
{{-- SECTION 4: BENEFITS --}}
{{-- ========================= --}}
<div class="w-full bg-[#F7F5F2] mt-10 lg:mt-30">
    <div class="flex flex-col items-center">

        {{-- Header --}}
        <div class="flex flex-col items-center text-center mt-16 px-6 md:px-12 lg:px-20 xl:px-40">
            <h1 class="Playfair text-3xl lg:text-5xl text-[#222]">
                The Benefits of <br /> Our Treatments
            </h1>

            <p class="Lato text-sm md:text-base text-[#444] mt-5 max-w-2xl">
                Our treatments do more than treat hair — they restore its balance and inner vitality.
            </p>
        </div>

        @php
            $benefits = [
                [
                    "image" => "https://cdn-icons-png.flaticon.com/128/1490/1490749.png",
                    "name" => "Deeper Relaxation",
                    "description" => "Each session eases physical and emotional tension creating space for true rest."
                ],
                [
                    "image" => "https://cdn-icons-png.flaticon.com/128/10176/10176866.png",
                    "name" => "Holistic Wellbeing",
                    "description" => "We blend touch, scent, and space in harmony to restore both body and mind."
                ],
                [
                    "image" => "https://cdn-icons-png.flaticon.com/128/9813/9813286.png",
                    "name" => "Natural Radiance",
                    "description" => "Circulation-boosting care enhances your glow from within."
                ],
                [
                    "image" => "https://cdn-icons-png.flaticon.com/128/14528/14528562.png",
                    "name" => "Mindful Moments",
                    "description" => "Our space invites you to slow down and reconnect with what truly matters."
                ],
            ];
        @endphp

        {{-- Benefits Grid --}}
        <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10 xl:gap-14 mt-10 p-5 mb-16">
            @foreach($benefits as $benefit)
                <div class="bg-white rounded-2xl p-8 flex flex-col items-center text-center shadow-md hover:shadow-lg transition hover:-translate-y-2">

                    <div class="bg-[#D8CEC4] p-4 rounded-full">
                        <img src="{{ $benefit['image'] }}" class="w-10 h-10 md:w-12 md:h-12">
                    </div>

                    <h1 class="Playfair mt-6 font-semibold">
                        {{ $benefit['name'] }}
                    </h1>

                    <p class="Lato text-sm text-[#525252] mt-4 max-w-[80%]">
                        {{ $benefit['description'] }}
                    </p>

                </div>
            @endforeach
        </div>
    </div>
</div>