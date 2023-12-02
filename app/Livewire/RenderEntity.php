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
    public $properties;
    private $service;
    public $malware;
    public $loadingEntity = false;
    public $menu = [
        'technique' => [
            'counter' => 1,
            'isOpen' => false,
        ],
        'software' => [
            'counter' => 1,
            'isOpen' => false,
        ],
        'mitigator' => [
            'counter' => 1,
            'isOpen' => false,
        ],
        'mitigates' => [
            'counter' => 1,
            'isOpen' => false,
        ],
        'citation' => [
            'counter' => 1,
            'isOpen' => false,
        ],
    ];

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
        $this->resetOpenMenu();
        $this->malware = $this->service->getCleanMalwareProperties($id);
        //sleep(5);
    }

    public function toggleMenu($chosenMenu)
    {
        // Counter, lebo z nejakeho dovodu sa vola funkcia 2x pri stlaceni
        $this->menu[$chosenMenu]['counter'] += 0.5;
        $this->menu[$chosenMenu]['isOpen'] = $this->menu[$chosenMenu]['counter'] % 2 == 0 ? true : false;
        if($this->menu[$chosenMenu]['counter'] == 3) $this->menu[$chosenMenu]['counter'] = 1;
    }

    public function resetOpenMenu()
    {
        $this->menu = [
            'technique' => [
                'counter' => 1,
                'isOpen' => false,
            ],
            'software' => [
                'counter' => 1,
                'isOpen' => false,
            ],
            'mitigator' => [
                'counter' => 1,
                'isOpen' => false,
            ],
            'mitigates' => [
                'counter' => 1,
                'isOpen' => false,
            ],
            'citation' => [
                'counter' => 1,
                'isOpen' => false,
            ],
        ];
    }
}
