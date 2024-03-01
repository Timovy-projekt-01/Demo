<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Ontologies\Helpers\HttpService;
use Livewire\Attributes\Validate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Visibility;
use App\Ontologies\Handlers\Parser;

class UploadOntology extends Component
{

    use WithFileUploads;

    public $error;
    public $ontologyFile;

    public function uploadFile(Request $request)
    {
        //dd($request);
        //todo idk store, call postOwl, delete file
        $path = $this->ontologyFile->storeAs('', $this->ontologyFile->getFileName(), 'public');
        $success = (new HttpService())->postOwl($path);
        if(!$success){
            Storage::disk('public')->delete($path);
            $this->error = 'Error';
            return;
        }
        Storage::disk('public')->delete($path);
        return redirect()->route('update');
    }

    public function createOwlConfig()
    {
        $this->validate([
            'ontologyFile' => 'required|file|max:204800',
        ]);
        $originalName = $this->ontologyFile->getClientOriginalName();
        if (!Storage::putFileAs('ontology/owlFiles', $this->ontologyFile, $originalName, Visibility::PRIVATE)) {
            $this->error = 'Error';
            return;
        }
        if (!Parser::createOntologyConfig($originalName)) {
            $this->error = 'Error';
            return;
        }
        
    }

    public function render()
    {
        return view('livewire.upload-ontology');
    }
}
