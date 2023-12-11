<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;


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

    #[On('add-to-history')]
    public function addToHistory($history)
    {
        $this->history = $history;
    }
}
