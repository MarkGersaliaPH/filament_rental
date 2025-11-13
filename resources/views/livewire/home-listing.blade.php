<div>
    <!-- Listings Section -->
    <main class="px-6 sm:px-[100px] py-10 space-y-10">

        <div class=" flex gap-2 items-center border rounded-full px-4 py-2 shadow-sm w-full ">
            <input type="text" placeholder="Search destinations"
                class="w-full outline-none text-gray-600 placeholder-gray-400 " />
            <button class="bg-teal-500 text-white p-2 rounded-full hover:bg-teal-600 transition">🔍</button>
        </div>



        <!-- Popular homes -->
        <section>
            <h2 class="text-xl font-semibold mb-4">
                Popular homes in Silang Junction South
            </h2>

            <!-- Scroll container for mobile -->
            <div class="flex overflow-x-auto sm:grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 scrollbar-hide">
                @foreach ($featured as $data)
                <div wire:key="{{ $data->id }}" class="flex-shrink-0 w-64 sm:w-auto">
                    <!-- Card -->
                    <div class="bg-white rounded-xl overflow-hidden shadow hover:shadow-lg transition">
                        <div class="relative">
                            <img src="https://images.pexels.com/photos/276724/pexels-photo-276724.jpeg?auto=compress&cs=tinysrgb&w=800"
                                alt="Home" class="w-full h-48 object-cover" />
                            <span
                                class="absolute top-2 left-2 bg-white text-sm px-2 py-1 rounded-md font-medium text-gray-700 shadow">Guest
                                favorite</span>
                        </div>
                        <div class="p-4">
                            <p class="font-semibold text-gray-800">{{$data->title}}</p>
                            <p class="text-sm text-gray-500">
                                ₱{{$data->moneyFormat('rent_amount')}}
                            </p>
                            <p class="text-sm text-gray-500">
                                Available on: {{$data->formattedData('available_from')}}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

           <!-- Popular homes -->
        <section>
            <h2 class="text-xl font-semibold mb-4">
                Available This Month
            </h2>

            <!-- Scroll container for mobile -->
            <div class="flex overflow-x-auto sm:grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 scrollbar-hide">
                @foreach ($availableThisMonth as $data)
                <div wire:key="{{ $data->id }}" class="flex-shrink-0 w-64 sm:w-auto">
                    <!-- Card -->
                    <div class="bg-white rounded-xl overflow-hidden shadow hover:shadow-lg transition">
                        <div class="relative">
                            <img src="https://images.pexels.com/photos/276724/pexels-photo-276724.jpeg?auto=compress&cs=tinysrgb&w=800"
                                alt="Home" class="w-full h-48 object-cover" />
                            <span
                                class="absolute top-2 left-2 bg-white text-sm px-2 py-1 rounded-md font-medium text-gray-700 shadow">Guest
                                favorite</span>
                        </div>
                        <div class="p-4">
                            <p class="font-semibold text-gray-800">{{$data->title}}</p>
                            <p class="text-sm text-gray-500">
                                ₱{{$data->moneyFormat('rent_amount')}}
                            </p>
                            <p class="text-sm text-gray-500">
                                Available on: {{$data->formattedData('available_from')}}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
 

        <section>
            
            <h2 class="text-xl font-semibold mb-4">
                Available For Rent
            </h2> 
            <!-- Scroll container for mobile -->
            <div class="flex overflow-x-auto sm:grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 scrollbar-hide">
                @foreach ($availables as $data)
                <div wire:key="{{ $data->id }}" class="flex-shrink-0 w-64 sm:w-auto">
                    <!-- Card -->
                    <div class="bg-white rounded-xl overflow-hidden shadow hover:shadow-lg transition">
                        <div class="relative">
                            <img src="https://images.pexels.com/photos/276724/pexels-photo-276724.jpeg?auto=compress&cs=tinysrgb&w=800"
                                alt="Home" class="w-full h-48 object-cover" />
                            <span
                                class="absolute top-2 left-2 bg-white text-sm px-2 py-1 rounded-md font-medium text-gray-700 shadow">Guest
                                favorite</span>
                        </div>
                        <div class="p-4">
                            <p class="font-semibold text-gray-800">{{$data->title}}</p>
                            <p class="text-sm text-gray-500">
                                ₱{{$data->moneyFormat('rent_amount')}}
                            </p>
                            <p class="text-sm text-gray-500">
                                Available on: {{$data->formattedData('available_from')}}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </main>
</div>