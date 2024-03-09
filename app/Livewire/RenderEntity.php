<?php

namespace App\Livewire;

use App\Ontologies\Handlers\Parser;
use Livewire\Component;
use App\Ontologies\Handlers\Queries;
use App\Ontologies\Handlers\Service;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;

class RenderEntity extends Component
{
    public $entity;

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
    public function showNewEntity($id, Service $service)
    {
        $this->entity = $service->getCleanEntityProperties($id);
        dd($this->entity);
        $this->dispatch('newSearch', $this->entity);

    }

    #[On('show-loaded-entity')]
    public function showLoadedEntity($entityFromHistory)
    {
        $this->entity = $entityFromHistory;
    }
}
