<?php

namespace App\Livewire;

use Livewire\Component;
use App\Ontologies\Malware\Queries;
use App\Ontologies\Malware\Service;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;

class RenderEntity extends Component
{
    public $properties;
    private $service;
    public $malware;
    public $isOpen = false;

    public function boot(Service $service)
    {
        $this->service = $service;
    }
    public function render()
    {
        return view('livewire.render-entity');
    }

    #[On('show-entity')]
    public function showEntireEntity($id)
    {
        $this->malware = $this->service->getCleanMalwareProperties($id);
    }
    public function toggleTechniques()
    {
        //dump($this->isOpen);
        $this->isOpen = !$this->isOpen;
    }



}
