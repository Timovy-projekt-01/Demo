<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Ontologies;
use Livewire\Attributes\On;

class SearchBar extends Component
{
    public $searchTerm = '';
    public $counter = 0;
    public $entitiesFromSearch = '';
    public $properties;
    private $sparql;

    public function boot(Ontologies\Malware\Queries $sparql)
    {
        $this->sparql = $sparql;
    }
    public function render()
    {
        if ($this->searchTerm != '') {
            $this->entitiesFromSearch = $this->sparql->searchEntities($this->searchTerm);
        }
        return view('livewire.search-bar');
    }

    public function showEntireEntity($malwareId)
    {
        $properties = $this->sparql->getMalwareProperties($malwareId);
        $this->dispatch('show-entity', $properties)->to(RenderEntity::class);
        $this->searchTerm = '';
    }

}
