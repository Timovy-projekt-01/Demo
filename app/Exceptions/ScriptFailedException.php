<?php

namespace App\Exceptions;

use Exception;

class ScriptFailedException extends Exception
{

    protected $body;

    public function __construct($message, $body){
        parent::__construct($message);
        $this->body = $body;
    }

    public function getBody(){
        return $this->body;
    }
}
