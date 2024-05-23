<?php

namespace App\Livewire;

use App\Models\OntologyConfig;
use Livewire\Component;

class ConfigEdit extends Component
{
    const SEARCHABLE = 'searchable';
    public $content_single;
    public $content_array;
    public $searchable;
    public $message;
    public $config;
    public $map;

    public function mount($config)
    {
        $this->config = OntologyConfig::find($config);
        $content = json_decode($this->config->content, true);
        $this->parseContent($content);
    }


    protected function parseContent($content)
    {
        $cnt = 0;
        foreach ($content as $key => $value) {
            if(is_array($value)){
                if($key === self::SEARCHABLE){
                    $this->searchable = implode(',', $value);
                    $this->content_array[$key] = $value;
                    continue;
                }
                foreach ($value as $property => $p_val){
                    $this->map[$cnt] = $property;
                    $this->content_array[$key][$cnt] = $p_val;
                    $cnt++;
                }
                continue;
            }
            $this->content_single[$key] = $value;
        }
    }

    public function submit()
    {
        $data = [];
        $cnt = 0;
        foreach($this->content_array as $key => $value){
            if($key === self::SEARCHABLE){
                $data[self::SEARCHABLE] = $this->content_array[self::SEARCHABLE] = explode(',', $this->searchable) ?? [];
                continue;
            }
            foreach($value as $property => $p_val){
                $data[$key][$this->map[$cnt]] = $p_val;
                $cnt++;
            }
        }
        $content = array_merge($this->content_single, $data);
        $this->config->name = $content['name'];
        $this->config->content = json_encode($content);
        $this->config->save();
        $this->message = 'Config updated';
    }

    public function render()
    {
        return view('livewire.config-edit');
    }
}
