<?php

use App\Livewire\Properties;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('/properties/{slug}',Properties::class)

// Route::get('/', function () {
    
// $customer = new Buyer([
//     'name'          => 'John Doe',
//     'custom_fields' => [
//         'email' => 'test@example.com',
//     ],
// ]);

// $item = InvoiceItem::make('Rent')->pricePerUnit(2);

// $item2 = InvoiceItem::make('Rent')->pricePerUnit(200);

// $invoice = Invoice::make()
//     ->buyer($customer)
//     ->seller($customer)
//     ->logo('images/logo.png')
//     ->currencyCode('PHP')
//     ->addItem($item)
//     ->addItem($item2);

// return $invoice->stream();
// })->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';
