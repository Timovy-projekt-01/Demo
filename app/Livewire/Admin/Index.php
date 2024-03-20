<?php

namespace App\Livewire\Admin;

use App\Models\UserFile;
use Livewire\Component;

class Index extends Component
{
    public $files;

    public function mount()
    {
        if (auth()->user()->role != 'superAdmin') {
            $this->files = auth()->user()->files;
        } else {
            $this->files = UserFile::all();
        }

    }

    public function render()
    {
        return view('livewire.admin.index');

    }
}
