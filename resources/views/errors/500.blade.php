<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Oops! Something went wrong</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#222222] text-white flex items-center justify-center min-h-screen px-4">

    <div class="text-center max-w-lg">
        
        <!-- Big Error Code -->
        <h1 class="text-6xl font-bold mb-4">500</h1>

        <!-- Message -->
        <h2 class="text-2xl font-semibold mb-3">
            Uh-oh! Something went wrong 😔
        </h2>

        <p class="text-gray-300 mb-6">
            We're having a little trouble on our end right now. 
            Please try again in a few moments.
        </p>

        <!-- Actions -->
        {{-- <div class="flex flex-col sm:flex-row gap-3 justify-center">
            
            <a href="/" 
               class="bg-[#F0CCCE] text-black px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition">
                Go Home
            </a>

            <button onclick="location.reload()" 
                class="border border-gray-400 px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                Try Again
            </button>

        </div> --}}

        <!-- Optional Note -->
        <p class="text-xs text-gray-500 mt-6">
            If the problem persists, please contact support.
        </p>

    </div>

</body>
</html>