<?php

namespace App\Traits;

use App\Exceptions\ScriptFailedException;
use Illuminate\Support\Facades\Process;

trait Script
{
    use Path;

    /**
     * @throws ScriptFailedException
     */
    public static function runPythonScript(string $script_name, array $args, string $should_return = null): string
    {
        $command = getenv('PYTHON_COMMAND') . ' ' . $script_name . ' ' . implode(' ', $args);
        $result = Process::path(self::getScriptsPath())->env([
            'SYSTEMROOT' => getenv('SYSTEMROOT'),
            'PATH' => getenv('PATH'),
        ])->run($command);
        if (!$result->successful()){
            self::throwError($result->errorOutput(), $script_name, $args);
        }
        //no need to return anything but w/e
        return ($should_return != null) ? $should_return : $result->output();
    }

    /**
     * @throws ScriptFailedException
     */
    public static function runCurlScript(array $args): bool
    {
        $result = Process::run('curl' . ' ' . implode(' ', $args));
        if (!$result->successful()){
            self::throwError($result->errorOutput(), 'curl', $args);
        }
        //no need to return anything but w/e
        return $result->successful();
    }


    /**
     * @throws ScriptFailedException
     */
    protected static function throwError(string $error, string $script_name, array $args): void
    {
        throw new ScriptFailedException('Failed to execute script!', [
            'response' => [
                'error' => true,
                'script_name' => $script_name,
                'arguments' => $args,
                'error_message' => $error,
            ],
        ]);
    }
}


