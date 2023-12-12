<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Ontologies;
use Livewire\Attributes\On;

class SearchBar extends Component
{
    public $searchTerm = '';
    public $entitiesFromSearch = [];
    private $sparql;

    public function boot(Ontologies\Malware\Queries $sparql)
    {
        $this->sparql = $sparql;
    }
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
            $this->entitiesFromSearch = $this->sparql->searchEntities($this->searchTerm, "");
        }
    }

    public function showMoreResults()
    {
        $idsToExclude = array_map(function ($entity) {
            return $entity['entity']['value'];
        }, $this->entitiesFromSearch);

        $entitiesToExclude = implode(" ", array_map(fn ($id) => "<http://stufei/ontologies/malware#{$id}>", $idsToExclude));

        $moreResults = $this->sparql->searchEntities($this->searchTerm, (string) $entitiesToExclude);
        $this->entitiesFromSearch = array_merge($this->entitiesFromSearch, $moreResults);
    }

}
