<div class="w-full bg-black text-white">
    <!-- Main Footer Section -->
    <footer class="flex flex-col lg:flex-row justify-between gap-y-6 gap-x-10 py-10 px-6 lg:px-16 border-b border-gray-700">
        
        <!-- Navigation -->
        <div class="flex flex-col gap-y-2">
            <h1 class="Playfair text-lg mb-1">NAVIGATION</h1>
            <a href="{{ url('/') }}" class="Lato hover:text-[#F0CCCE]">Home</a>
            
            {{-- Scroll logic: If on home, use ID anchor. If not, go to home + ID --}}
            <a href="{{ Request::is('/') ? '#services' : url('/#services') }}" class="Lato hover:text-[#F0CCCE]">
                Services
            </a>
            
            {{-- <a href="{{ url('/booking') }}" class="Lato hover:text-[#F0CCCE]">Bookings</a>
            <a href="{{ url('/payment') }}" class="Lato hover:text-[#F0CCCE]">Payment</a> --}}
            <a href="{{ Request::is('/') ? '#contact' : url('/#contact') }}" class="Lato hover:text-[#F0CCCE]">
                Contact Us
            </a>
        </div>

        <!-- Socials -->
        <div class="flex flex-col gap-y-2">
            <h1 class="Playfair text-lg mb-1">FOLLOW US</h1>
            <a href="https://www.instagram.com/tn_healthyhairstudio/" class="Lato hover:text-[#F0CCCE]">Instagram</a>
            {{-- <a href="#" class="Lato hover:text-[#F0CCCE]">YouTube</a>
            <a href="#" class="Lato hover:text-[#F0CCCE]">TikTok</a> --}}
            <a href="https://share.google/0CJql6zx6DfovEnen" class="Lato hover:text-[#F0CCCE]">Google My Business</a>
        </div>

        <!-- Contact Info -->
        <div class="flex flex-col gap-y-2">
            <h1 class="Playfair text-lg mb-1">CONTACT US</h1>
            <p class="Lato">+234 703 542 1098</p>
            <p class="Lato">tnhealthyhairstudio@gmail.com</p>
        </div>

        <!-- Logo + Address -->
        <div class="flex flex-col gap-y-2 w-full md:w-auto">
            <div class="flex flex-row items-center gap-x-2">
                <img src="{{ asset('images/TN-Skincare logo.webp') }}" alt="logo" class="w-8 h-8 rounded-full border border-[#F0CCCE]" />
                <h1 class="Playfair text-lg uppercase">TN HEALTHY HAIR STUDIO</h1>
            </div>
            <p class="Lato text-sm">
                11 Chief Onitana Off Adebola Street Off Adeniran ,<br />Surulere,<br />Lagos,<br />Nigeria
            </p>
        </div>

        <!-- Opening Hours -->
        <div class="flex flex-col gap-y-2">
            <h1 class="Playfair text-lg mb-1">OPENING HOURS</h1>
            <p class="Lato text-sm">
                Wednesday To Saturday<br />9:00AM - 12:00PM
            </p>
        </div>
    </footer>

    <!-- Bottom Copyright Bar -->
    <div class="w-full flex justify-center items-center py-4 bg-black text-gray-400 border-t border-gray-800">
        <p class="Lato text-xs text-center">
            © {{ date('Y') }} TN Healthy Hair Studio. All rights reserved.
        </p>
    </div>
</div>
