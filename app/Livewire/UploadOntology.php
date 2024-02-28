<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Ontologies\Helpers\HttpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadOntology extends Component
{
    
    use WithFileUploads;

    public $error;

    public function uploadFile(Request $request)
    {   
        dd($request);
        //todo idk store, call postOwl, delete file
        $path = $this->ontology_file->storeAs('', $this->ontology_file->getFileName(), 'public');
        $success = (new HttpService())->postOwl($path);
        if(!$success){
            Storage::disk('public')->delete($path);
            $this->error = 'Error';
            return; 
        }
        Storage::disk('public')->delete($path);
        return redirect()->route('update');
    }


    public function render()
    {
        return view('livewire.upload-ontology');
    }
}
