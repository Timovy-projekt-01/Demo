<?php

namespace App\Traits;

trait Path
{
    public static function getScriptsPath(): string
    {
        return app_path() . '\bin';
    }
}
