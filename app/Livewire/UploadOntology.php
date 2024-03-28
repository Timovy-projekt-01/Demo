<?php

namespace App\Livewire;

use App\Exceptions\FileSystemException;
use App\Exceptions\ScriptFailedException;
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
        try{
            $file_names = $this->saveFile();
            if($this->action_add){
                $this->createOwlConfig($file_names['original_name']);
                $userFile = new UserFile();
                $userFile->name = $file_names['original_name'];
                $userFile->user_id = auth()->user()->id;
                $userFile->path = $file_names['full_path'];
                $userFile->save();
            }
            $this->getHttpService()->postOwl($file_names['full_path']);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
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
            throw new FileSystemException('Error saving file', []);
        }

        return [
            'full_path' => Storage::path($path),
            'original_name' => $originalName
        ];
    }

    public function createOwlConfig(string $originalName): void
    {
        if (!Parser::createOntologyConfig($originalName)) {
            //todo move to parser
            //todo add translations
            throw new ScriptFailedException('Failed to create config', [
                'response' => [
                    'error' => true,
                    'script_name' => 'createOntologyConfig.py',
                    'ontology' => $originalName,
                    'return_var' => null,
                ],
            ]);
        }
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
