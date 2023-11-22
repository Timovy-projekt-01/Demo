<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\Malware\Sparql;
use App\Services\HttpService;
use Illuminate\Support\Facades\Cache;
class Test extends Component
{
    public $results;
    public $input = '';
    private $sparql;
    public function boot(Sparql $sparql)
    {
        $this->sparql = $sparql;
    }
    public function render()
    {
        return view('livewire.test');
    }

    public function fetchData()

        // ...

    {
        dd(Cache::get('malware'));
        $this->results = $this->sparql->searchEntities($this->input);


    }
}
