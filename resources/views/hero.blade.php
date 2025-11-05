<div x-data="{ activeSlide: 0, totalSlides: 5 }" class="relative w-full overflow-hidden">
    <div
        class="flex transition-transform duration-500 ease-in-out"
        :style="`transform: translateX(-${activeSlide * 100 / totalSlides}%)`"
    >
        <div class="w-full flex-shrink-0 p-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://via.placeholder.com/600x400/FF5733/FFFFFF?text=Property+1" alt="Property 1" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="font-semibold text-lg">Modern Condo</h3>
                    <p class="text-gray-600 text-sm">Tagaytay | ₱5,000/night</p>
                </div>
            </div>
        </div>

        <div class="w-full flex-shrink-0 p-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://via.placeholder.com/600x400/33FF57/FFFFFF?text=Property+2" alt="Property 2" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="font-semibold text-lg">Cozy Apartment</h3>
                    <p class="text-gray-600 text-sm">Manila | ₱3,500/night</p>
                </div>
            </div>
        </div>

        <div class="w-full flex-shrink-0 p-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://via.placeholder.com/600x400/3357FF/FFFFFF?text=Property+3" alt="Property 3" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="font-semibold text-lg">Spacious Villa</h3>
                    <p class="text-gray-600 text-sm">Batangas | ₱8,000/night</p>
                </div>
            </div>
        </div>

        <div class="w-full flex-shrink-0 p-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://via.placeholder.com/600x400/FFFF33/000000?text=Property+4" alt="Property 4" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="font-semibold text-lg">Beachfront House</h3>
                    <p class="text-gray-600 text-sm">Palawan | ₱12,000/night</p>
                </div>
            </div>
        </div>

        <div class="w-full flex-shrink-0 p-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://via.placeholder.com/600x400/AA33FF/FFFFFF?text=Property+5" alt="Property 5" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="font-semibold text-lg">Urban Loft</h3>
                    <p class="text-gray-600 text-sm">Makati | ₱6,500/night</p>
                </div>
            </div>
        </div>
    </div>

    <button
        @click="activeSlide = activeSlide === 0 ? totalSlides - 1 : activeSlide - 1"
        class="absolute top-1/2 left-0 -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white p-2 rounded-r-lg focus:outline-none"
    >
        &#10094; </button>
    <button
        @click="activeSlide = activeSlide === totalSlides - 1 ? 0 : activeSlide + 1"
        class="absolute top-1/2 right-0 -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white p-2 rounded-l-lg focus:outline-none"
    >
        &#10095; </button>

    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
        <template x-for="slide in totalSlides" :key="slide">
            <button
                @click="activeSlide = slide - 1"
                :class="{ 'bg-blue-500': activeSlide === slide - 1, 'bg-gray-300': activeSlide !== slide - 1 }"
                class="w-3 h-3 rounded-full"
            ></button>
        </template>
    </div>
</div>