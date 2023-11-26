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
        $trimmedSearch = trim($this->searchTerm);
        if ($trimmedSearch != "") {
            $this->entitiesFromSearch = $this->sparql->searchEntities($trimmedSearch);
        }
        return view('livewire.search-bar');
    }

    public function clearSearch()
    {
        $this->searchTerm = '';
    }

    public function showEntireEntity($malwareId)
    {
        $properties = $this->sparql->getMalwareProperties($malwareId);
        $this->dispatch('show-entity', $properties)->to(RenderEntity::class);
        $this->clearSearch();
    }

}
