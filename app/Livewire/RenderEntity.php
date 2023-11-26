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
    public $counter = 1;
    public $isOpen = false;

    public function boot(Service $service)
    {
        $this->service = $service;
    }
    public function render()
    {
        return view('livewire.render-entity');
    }

    /**
     * Retrieves and displays the entire entity with the given ID.
     *
     * @param int $id The ID of the entity to retrieve.
     * @return void
     */
    #[On('show-entity')]
    public function showEntireEntity($id)
    {
        $this->resetOpenMenu();
        $this->malware = $this->service->getCleanMalwareProperties($id);
    }

    public function toggleTechniques()
    {
        $this->counter = $this->counter + 0.5;
        $this->isOpen = $this->counter % 2 == 0 ? true : false;
    }

    public function resetOpenMenu()
    {
        $this->counter = 1;
        $this->isOpen = false;
    }
}
