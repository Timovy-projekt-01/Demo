<?php

namespace App\Livewire;

use App\Models\OntologyConfig;
use Livewire\Component;

class ConfigEdit extends Component
{
    const SEARCHABLE = 'searchable';
    public $content_single;
    public $content_array;
    public $message;
    public $config;

    public function mount($config)
    {
        $this->config = OntologyConfig::find($config);
        $content = json_decode($this->config->content, true);
        $this->parseContent($content);
    }


    protected function parseContent($content)
    {
        foreach ($content as $key => $value) {
            if(is_array($value)){
                $this->content_array[$key] = $value;
                continue;
            }
            $this->content_single[$key] = $value;
        }
    }

    public function submit()
    {
        $content = array_merge($this->content_single, $this->content_array);
        $content[self::SEARCHABLE] = explode(',', is_array($content[self::SEARCHABLE]) ? '' : $content[self::SEARCHABLE]);
        $this->config->content = json_encode($content);
        $this->config->save();
        $this->message = 'Config updated';
    }

    public function render()
    {
        return view('livewire.config-edit');
    }
}
