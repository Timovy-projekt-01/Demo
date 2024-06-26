<?php

namespace App\Livewire;

use Livewire\Component;
use App\Ontologies\Handlers\Service;
use Livewire\Attributes\On;

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
        $this->dispatch('newSearch', $this->entity);

    }

    #[On('show-loaded-entity')]
    public function showLoadedEntity($entityFromHistory)
    {
        $this->entity = $entityFromHistory;
    }
}
