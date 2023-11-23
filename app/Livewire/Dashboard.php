<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Services\Malware\Sparql;
class Dashboard extends Component
{
    public $filter;
    public $filterItems = '';
    public $items;
    public $counter = 0;
    public $entitiesFromSearch = '';
    public $properties;
    private $sparql;

    public function boot(Sparql $sparql)
    {
        $this->sparql = $sparql;
    }
    public function render()
    {
        if($this->filterItems != ''){
            $this->entitiesFromSearch = $this->sparql->searchEntities($this->filterItems);
        }
        return view('livewire.dashboard');
    }

    public function showEntireEntity($malwareId) {

        $this->properties = $this->sparql->getMalwareProperties($malwareId);
        $this->filterItems = '';
    }
}
