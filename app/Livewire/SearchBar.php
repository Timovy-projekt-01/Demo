<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Ontologies\Handlers\Queries;
use Livewire\Attributes\On;

class SearchBar extends Component
{
    public $searchTerm = '';
    public $entitiesFromSearch = [];
    public function render()
    {
        return view('livewire.search-bar');
    }

    public function clearSearch()
    {
        $this->searchTerm = '';
    }
    public function searchEntities()
    {
        $this->searchTerm = trim($this->searchTerm);
        if ($this->searchTerm != "") {
            $this->entitiesFromSearch = Queries::searchEntities($this->searchTerm, "");
        }
    }

    public function showMoreResults()
    {
        $idsToExclude = array_map(function ($entity) {
            return $entity['entity']['value'];
        }, $this->entitiesFromSearch);

        $entitiesToExclude = implode(" ", array_map(fn ($id) => "<http://stufei/ontologies/malware#{$id}>", $idsToExclude));

        $moreResults = Queries::searchEntities($this->searchTerm, (string) $entitiesToExclude);
        $this->entitiesFromSearch = array_merge($this->entitiesFromSearch, $moreResults);
    }

}
