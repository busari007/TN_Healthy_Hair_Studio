{{-- ========================= --}}
{{-- SECTION 6: GALLERY --}}
{{-- ========================= --}}
<div class="w-full bg-[#D8CEC4] flex flex-col items-center text-center py-16 px-4">

    {{-- Header --}}
    <h1 class="Playfair text-3xl md:text-4xl lg:text-5xl max-w-2xl">
        TN Healthy Hair Studio Gallery
    </h1>

    @php
        $images = [
            asset('images/GalleryImage1.webp'),
            asset('images/GalleryImage2.webp'),
            asset('images/GalleryImage4.webp'),
        ];
    @endphp

    {{-- Grid --}}
    <div class="w-full max-w-7xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-10">

        @foreach($images as $image)
            <div class="overflow-hidden rounded-2xl h-[250px] lg:h-[300px] group relative">

                <img src="{{ $image }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">

                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition"></div>

            </div>
        @endforeach

    </div>
</div>