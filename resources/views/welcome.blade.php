<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Property App</title>
  <style>
    body {
      background: #f9fafb;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
    }
  </style>
</head>
<body>
  <!-- HEADER (Full width on desktop) -->
  <header class="w-full bg-white shadow-sm">
    <div class="max-w-[420px] mx-auto p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-400">Location</p>
          <div class="flex items-center space-x-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c.828 0 1.5-.672 1.5-1.5S12.828 8 12 8s-1.5.672-1.5 1.5S11.172 11 12 11z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21s8-8.5 8-13a8 8 0 10-16 0c0 4.5 8 13 8 13z" />
            </svg>
            <span class="font-semibold text-gray-800 text-base">Yogyakarta, Ind</span>
          </div>
        </div>
        <div class="flex space-x-4">
          <button class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 rounded-full"></span>
          </button>
          <img src="https://i.pravatar.cc/40" alt="avatar" class="w-10 h-10 rounded-full">
        </div>
      </div>
    </div>
  </header>

  <!-- MAIN CONTENT -->
  <main class="w-full flex justify-center flex-grow">
    <div class="w-[420px] bg-gray-50 rounded-[2rem] overflow-hidden pb-24">
      <!-- Search -->
      <div class="px-5 mt-4">
        <div class="flex items-center bg-white shadow-sm rounded-xl px-4 py-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
          </svg>
          <input type="text" placeholder="Search Property" class="ml-3 w-full bg-transparent focus:outline-none text-base" />
          <button class="p-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm10.707 3.293a1 1 0 00-1.414 0L10 10.586 7.707 8.293a1 1 0 00-1.414 1.414L8.586 12l-2.293 2.293a1 1 0 101.414 1.414L10 13.414l2.293 2.293a1 1 0 001.414-1.414L11.414 12l2.293-2.293a1 1 0 000-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Banner -->
      <div class="px-5 mt-5">
        <div class="bg-purple-600 text-white rounded-2xl p-5 flex justify-between items-center">
          <div>
            <h3 class="text-xl font-semibold leading-tight">GET YOUR 20% CASHBACK</h3>
            <p class="text-sm opacity-80">*Expired 25 Aug 2022</p>
          </div>
          <img src="https://cdn-icons-png.flaticon.com/512/743/743131.png" class="w-20 h-20" />
        </div>
      </div>

      <!-- Recommended -->
      <div class="px-5 mt-7 flex justify-between items-center">
        <h2 class="font-semibold text-xl">Recommended</h2>
        <a href="#" class="text-purple-600 text-sm">See all</a>
      </div>
      <div class="px-5 mt-3 flex space-x-5 overflow-x-auto pb-2">
        <div class="min-w-[220px] bg-white rounded-2xl shadow-sm overflow-hidden">
          <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=400&q=60" class="w-full h-36 object-cover">
          <div class="p-4">
            <h3 class="font-semibold text-base">Ayana Homestay</h3>
            <p class="text-sm text-gray-500">Imogiri, Yogyakarta</p>
            <span class="text-purple-600 font-semibold text-base block mt-1">$310/month</span>
          </div>
        </div>
        <div class="min-w-[220px] bg-white rounded-2xl shadow-sm overflow-hidden">
          <img src="https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=400&q=60" class="w-full h-36 object-cover">
          <div class="p-4">
            <h3 class="font-semibold text-base">Bali Komang Villa</h3>
            <p class="text-sm text-gray-500">Nusa Pendida</p>
            <span class="text-purple-600 font-semibold text-base block mt-1">$350/month</span>
          </div>
        </div>
      </div>

      <!-- Nearby -->
      <div class="px-5 mt-7 flex justify-between items-center">
        <h2 class="font-semibold text-xl">Nearby</h2>
        <a href="#" class="text-purple-600 text-sm">See all</a>
      </div>
      <div class="px-5 mt-4 space-y-4">
        <div class="flex bg-white rounded-2xl shadow-sm overflow-hidden">
          <img src="https://images.unsplash.com/photo-1600607687920-4e3b5c4e3a63?auto=format&fit=crop&w=200&q=60" class="w-28 h-28 object-cover">
          <div class="p-4 flex flex-col justify-between">
            <div>
              <h3 class="font-semibold text-base">Maharani Villa</h3>
              <p class="text-sm text-gray-500">Bendungan, Yogyakarta</p>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-purple-600 font-semibold text-base">$320/month</span>
              <span class="text-yellow-400 text-sm">⭐ 4.5</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- FOOTER (Full width on desktop) -->
  <footer class="w-full bg-white shadow-inner fixed bottom-0 left-0">
    <div class="max-w-[420px] mx-auto flex justify-around py-3">
      <button class="flex flex-col items-center text-purple-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        <span class="text-xs mt-1">Home</span>
      </button>
      <button class="flex flex-col items-center text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-xs mt-1">Explore</span>
      </button>
      <button class="flex flex-col items-center text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
        <span class="text-xs mt-1">Favorite</span>
      </button>
      <button class="flex flex-col items-center text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 0112 15a4 4 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <span class="text-xs mt-1">Profile</span>
      </button>
    </div>
  </footer>
</body>
</html>
