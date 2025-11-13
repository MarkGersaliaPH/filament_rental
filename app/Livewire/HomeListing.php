<?php

namespace App\Livewire;

use App\Models\Property;
use Livewire\Component;

class HomeListing extends Component
{

    
    public $availableThisMonth;
    public $featured; 
    public $availables; 
    public function render()
    {
        $thisMonth = now()->startOfMonth();
        $endOfThisMonth = now()->endOfMonth(); 
        $this->featured = Property::featured()->get();
        $this->availables = Property::available()->get();
        $this->availableThisMonth = Property::whereBetween('available_from',[$thisMonth,$endOfThisMonth])->get();
        return view('livewire.home-listing');
    }

}
