<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Services\Malware\Sparql;
class Dashboard extends Component
{
    public $filter;
    public $filterItems;
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
        //filterItems is the value of the input field
        $this->filter = '%' . $this->filterItems . '%';
        //$this->items = Item::where('name', 'like', $this->filter)->get();
        $this->counter = $this->counter + 1;
        if($this->filterItems != ''){
            $this->entitiesFromSearch = $this->sparql->searchEntities($this->filterItems);

        }
        return view('livewire.dashboard');
    }

    public function show(){
        dd("couter = " . $this->counter, "filterItems= " . $this->filterItems, "filter= " . $this->filter, "items=",$this->items);
    }

    private function extractId($uri, $charToFind = '#')
    {
        if ($uri) {
            $hashtagIndex = strrpos($uri, $charToFind);
            return substr($uri, $hashtagIndex + 1);
        }
        return '';
    }
    public function showEntireEntity($malwareId) {
        dd("IDK");
        dump($malwareId);

        $this->properties = $this->sparql->getMalwareProperties($malwareId);
        dump($this->properties, "idk");
        //return view('livewire.dashboard');
    }
}
