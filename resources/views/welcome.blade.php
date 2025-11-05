<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Property App</title>
  <style>
    body { 
      min-height: 100vh;
      padding-bottom: 100px;
    }
  </style>
</head>
<body>
    
  @include('navigation') 
  @include('home-listings')

  <!-- FOOTER (Full width on desktop) -->
  <footer class="w-full bg-white shadow-inner fixed bottom-0 left-0">
    <div class="max-w-[420px] mx-auto flex justify-around py-3">
      <button class="flex flex-col items-center text-teal-600">
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
