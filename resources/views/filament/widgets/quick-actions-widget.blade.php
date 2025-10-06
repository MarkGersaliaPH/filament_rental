<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Actions
        </x-slot>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="/admin/properties/create" 
               class="flex flex-col items-center p-4 bg-primary-50 hover:bg-primary-100 rounded-lg border border-primary-200 transition-colors duration-200">
                <x-heroicon-o-home class="w-8 h-8 text-primary-600 mb-2"/>
                <span class="text-sm font-medium text-primary-900">Add Property</span>
            </a>

            <a href="/admin/customers/create" 
               class="flex flex-col items-center p-4 bg-success-50 hover:bg-success-100 rounded-lg border border-success-200 transition-colors duration-200">
                <x-heroicon-o-user-plus class="w-8 h-8 text-success-600 mb-2"/>
                <span class="text-sm font-medium text-success-900">Add Customer</span>
            </a>

            <a href="/admin/rentals/create" 
               class="flex flex-col items-center p-4 bg-warning-50 hover:bg-warning-100 rounded-lg border border-warning-200 transition-colors duration-200">
                <x-heroicon-o-document-plus class="w-8 h-8 text-warning-600 mb-2"/>
                <span class="text-sm font-medium text-warning-900">New Rental</span>
            </a>

            <a href="/admin/properties?tableFilters[status][value]=maintenance" 
               class="flex flex-col items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg border border-orange-200 transition-colors duration-200">
                <x-heroicon-o-wrench-screwdriver class="w-8 h-8 text-orange-600 mb-2"/>
                <span class="text-sm font-medium text-orange-900">Maintenance</span>
            </a>

            <a href="/admin/rentals?tableFilters[payment_status][value]=overdue" 
               class="flex flex-col items-center p-4 bg-danger-50 hover:bg-danger-100 rounded-lg border border-danger-200 transition-colors duration-200">
                <x-heroicon-o-exclamation-triangle class="w-8 h-8 text-danger-600 mb-2"/>
                <span class="text-sm font-medium text-danger-900">Overdue</span>
            </a>

            <a href="/admin/properties?tableFilters[status][value]=available" 
               class="flex flex-col items-center p-4 bg-emerald-50 hover:bg-emerald-100 rounded-lg border border-emerald-200 transition-colors duration-200">
                <x-heroicon-o-check-circle class="w-8 h-8 text-emerald-600 mb-2"/>
                <span class="text-sm font-medium text-emerald-900">Available</span>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>