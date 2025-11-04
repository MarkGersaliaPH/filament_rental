<?php

namespace App\Livewire;

use App\Models\User;
use Closure;
use Filament\Schemas\Components\View;
use Livewire\Component;

class SearchUsers extends Component
{
     public $search = '';

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
         return view('livewire.search-users', [
            'users' => User::search($this->search)->get(),
        ]);
    }
    
}
