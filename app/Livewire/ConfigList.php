<?php

namespace App\Livewire;

use App\Models\OntologyConfig;
use Livewire\Component;

class ConfigList extends Component
{
    public $files;

    public function mount()
    {
        $this->files = OntologyConfig::where('user_file_id', auth()->id())->get();
    }

    public function render()
    {
        return view('livewire.config-list');
    }
}
