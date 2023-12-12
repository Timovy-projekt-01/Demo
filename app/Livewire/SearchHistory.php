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

    public function retrieveLoadedEntity($id)
    {
        $loadedEntity = array_filter($this->history, function ($item) use ($id) {
            return $item[array_key_first($item)] == $id;
        });

        $this->dispatch('show-loaded-entity', reset($loadedEntity))->to(RenderEntity::class);
    }
}
