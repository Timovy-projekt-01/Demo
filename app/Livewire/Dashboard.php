<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;

class Dashboard extends Component
{
    public $filter;
    public $filterItems;
    public $items;
    public $counter = 0;

    public function render()
    {
        //filterItems is the value of the input field
        $this->filter = '%' . $this->filterItems . '%';
        $this->items = Item::where('name', 'like', $this->filter)->get();
        $this->counter = $this->counter + 1;
        return view('livewire.dashboard');
    }

    public function show(){
        dd("couter = " . $this->counter, "filterItems= " . $this->filterItems, "filter= " . $this->filter, "items=",$this->items);
    }
}
