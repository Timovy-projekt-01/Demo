<?php

namespace App\Livewire;

use App\Models\OntologyConfig;
use Livewire\Component;
use App\Models\UserFile;

class ConfigList extends Component
{
    public $files;

    public function mount()
    {
        $file_ids = UserFile::where('user_id', auth()->id())->pluck('id');
        $this->files = OntologyConfig::whereIn('user_file_id', $file_ids)->get();
    }

    public function render()
    {
        return view('livewire.config-list');
    }
}
