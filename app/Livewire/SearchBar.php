<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Ontologies;
class SearchBar extends Component
{
    public $filter;
    public $filterItems = '';
    public $items;
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
        if($this->filterItems != ''){
            $this->entitiesFromSearch = $this->sparql->searchEntities($this->filterItems);
        }
        return view('livewire.search-bar');
    }

    public function showEntireEntity($malwareId) {

        $this->properties = $this->sparql->getMalwareProperties($malwareId);
        $this->filterItems = '';
    }
}
