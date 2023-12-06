<?php

namespace App\Livewire;

use App\Ontologies\Malware\Parser;
use Livewire\Component;
use App\Ontologies\Malware\Queries;
use App\Ontologies\Malware\Service;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;

class RenderEntity extends Component
{
    private $service;
    public $entity;

    public function boot(Service $service)
    {
        $this->service = $service;
        // $parser = new Parser();
        // $parser->getPredicates();
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
        $this->malware = $this->service->getCleanMalwareProperties($id);
        $this->dispatch('newSearch', $this->malware);

    }
}
