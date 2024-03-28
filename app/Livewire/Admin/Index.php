<?php

namespace App\Livewire\Admin;

use App\Models\UserFile;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;


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

    public function deleteFile($id)
    {
        $file = UserFile::find($id);
        $path = $file->path;
        $file->delete();
        Storage::delete($path);
    }

    public function render()
    {
        return view('livewire.admin.index');

    }
}
