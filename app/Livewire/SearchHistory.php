<?php

namespace App\Livewire;

use Livewire\Component;

class SearchHistory extends Component
{
    public $history = [];

    protected $listeners = [
       're-render' => 'reRender',
    ];

    public function reRender()
    {
        $this->render();
    }

    public function render()
    {
        return view('livewire.search-history');
    }
}
