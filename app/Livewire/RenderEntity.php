<?php

namespace App\Livewire;

use App\Ontologies\Handler\Parser;
use Livewire\Component;
use App\Ontologies\Handler\Queries;
use App\Ontologies\Handler\Service;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;

class RenderEntity extends Component
{
    private $service;
    public $entity;

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
    #[On('show-new-entity')]
    public function showNewEntity($id)
    {
        $this->entity = $this->service->getCleanEntityProperties($id);
        $this->dispatch('newSearch', $this->entity);

    }

    #[On('show-loaded-entity')]
    public function showLoadedEntity($entityFromHistory)
    {
        $this->entity = $entityFromHistory;
    }
}
