{{-- ========================= --}}
{{-- SECTION 5: REVIEWS --}}
{{-- ========================= --}}
<div class="w-full mt-10 mb-16">

    @php
        $reviews = [
            [
                "description" => "From the moment I walked in, the warm greeting and the aroma of premium hair masques put me at ease...",
                "name" => "Isabelle M."
            ],
            [
                "description" => "The ultimate sanctuary for both my strands and my peace of mind...",
                "name" => "Clara H."
            ],
            [
                "description" => "After just one session in the chair, my hair felt so transformed and smooth...",
                "name" => "Elena T."
            ],
            [
                "description" => "It’s more than a salon — it’s a sanctuary where my hair and my spirit both get the care they deserve...",
                "name" => "Sophie D."
            ],
        ];
    @endphp

    <div class="flex flex-col items-center text-black">

        {{-- Header --}}
        <div class="w-[90%] md:w-[97.5%] h-[264px] bg-[#D8CEC4] mt-10 flex flex-col items-center text-center">
            <img src="https://cdn-icons-png.flaticon.com/128/7720/7720792.png" class="w-12 h-12 mt-8">

            <h1 class="Playfair w-[80%] text-3xl lg:text-4xl mt-4">
                What Our Guests Are Saying
            </h1>
        </div>

        {{-- Reviews Grid --}}
        <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 place-items-center mt-10 gap-y-5">

            @foreach($reviews as $review)
                <div class="w-[90%] h-[264px] lg:h-[280px] flex flex-col items-center text-center bg-white justify-between p-7">

                    <div>
                        <img src="{{ asset('images/5stars.png') }}" class="w-20 mb-3 mx-auto">

                        <p class="Lato text-sm">
                            {{ $review['description'] }}
                        </p>
                    </div>

                    <p class="text-xs mt-3">
                        {{ $review['name'] }}
                    </p>

                </div>
            @endforeach

        </div>
    </div>
</div>