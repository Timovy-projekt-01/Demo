<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Ontologies\Handlers\Queries;
use Livewire\Attributes\On;
use App\Ontologies\Handlers\Service;

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
    public function searchEntities(Service $service)
    {
        $this->searchTerm = trim($this->searchTerm);
        if ($this->searchTerm != "") {
            $this->entitiesFromSearch = $service->searchEntities($this->searchTerm, "");
        }
    }

    public function showMoreResults(Service $service)
    {
        $idsToExclude = array_map(function ($entity) {
            return $entity['uri'];
        }, $this->entitiesFromSearch);

        $entitiesToExclude = implode(" ", array_map(fn ($id) => "<{$id}>", $idsToExclude));

        $moreResults = $service->searchEntities($this->searchTerm, (string) $entitiesToExclude);
        $this->entitiesFromSearch = array_merge($this->entitiesFromSearch, $moreResults);
    }

}
