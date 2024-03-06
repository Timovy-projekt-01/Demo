<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Ontologies\Helpers\HttpService;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Visibility;
use App\Ontologies\Handlers\Parser;

class UploadOntology extends Component
{

    use WithFileUploads;

    public $error;
    public $ontologyFile;

    public function uploadFile()
    {
        $file_names = $this->saveFile();

        if(
            is_null($file_names)
            || !$this->createOwlConfig($file_names['original_name'])
            || !$this->uploadOwlFile($file_names['full_path'])
        ){
            Storage::delete($file_names['full_path']);
            return;
        }

        return redirect()->route('update');
    }

    protected function saveFile(): ?array
    {
        $this->validate([
            'ontologyFile' => 'required|file|max:204800',
        ]);

        $originalName = $this->ontologyFile->getClientOriginalName();
        if (!($path = Storage::putFileAs('ontology/owlFiles', $this->ontologyFile, $originalName, Visibility::PRIVATE))) {
            $this->error = 'Error saving file...';
            return null;
        }

        return [
            'full_path' => Storage::path($path),
            'original_name' => $originalName
        ];
    }

    protected function uploadOwlFile(string $full_path): bool
    {
        $success = (new HttpService())->postOwl($full_path);
        if(!$success){
            $this->error = 'Error uploading file...';
            return false;
        }
        return true;
    }

    public function createOwlConfig(string $originalName): bool
    {
        if (!Parser::createOntologyConfig($originalName)) {
            $this->error = 'Error';
            return false;
        }
        return true;
    }

    public function render()
    {
        return view('livewire.upload-ontology');
    }
}
