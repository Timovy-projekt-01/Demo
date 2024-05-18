<?php

namespace App\Livewire;

use App\Exceptions\FileSystemException;
use App\Exceptions\ScriptFailedException;
use App\Models\OntologyConfig;
use App\Models\UserFile;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Ontologies\Helpers\HttpService;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Visibility;
use App\Ontologies\Handlers\Parser;
use Exception;

class UploadOntology extends Component
{

    use WithFileUploads;

    public $error;

    public $ontologyFile;

    public $action_add;

    public function mount()
    {
        $this->action_add = true;
    }

    public function action()
    {
        $this->action_add = !$this->action_add;
    }

    public function uploadFile()
    {
        $type = $this->action_add ? 'owlTemplates' : 'owlFiles';
        try{
            $file_names = $this->saveFile($type);
            if($this->action_add){
                $this->createUserFile($file_names);
                $this->createOwlConfig($file_names['original_name']);
            }
            $this->getHttpService()->postOwl($file_names['full_path']);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            Storage::delete($file_names['full_path']);
            return;
        }

        return redirect()->route('update');
    }

    protected function createUserFile(array $file_names): void
    {
        UserFile::create([
            'name' => $file_names['original_name'],
            'user_id' => auth()->user()->id,
            'path' => $file_names['full_path'],
        ]);
    }

    protected function saveFile(string $type): ?array
    {
        $this->validate([
            'ontologyFile' => 'required|file|max:204800',
        ]);

        $originalName = $this->ontologyFile->getClientOriginalName();
        if (!($path = Storage::putFileAs('ontology/' . $type . '/' . auth()->user()->id, $this->ontologyFile, $originalName, Visibility::PRIVATE))) {
            throw new FileSystemException('Error saving file', []);
        }

        return [
            'full_path' => Storage::path($path),
            'original_name' => $originalName
        ];
    }

    public function createOwlConfig(string $originalName): void
    {
        Parser::createOntologyConfig($originalName);
    }

    protected function getHttpService()
    {
        return new HttpService();
    }

    public function render()
    {
        return view('livewire.upload-ontology');
    }
}
