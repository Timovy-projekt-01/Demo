<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;


class SearchHistory extends Component
{
    public $history = [];

    public function render()
    {
        return view('livewire.search-history');
    }

    #[On('update-history')]
    public function updateHistory($history)
    {
        $this->history = $history;
    }

    #[On('clear-history')]
    public function clearHistory()
    {
        $this->history = [];
    }
}
