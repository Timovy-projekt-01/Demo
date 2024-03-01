<?php

namespace App\Ontologies\Handlers;

use App\Exceptions\ScriptFailedException;
use Illuminate\Support\Facades\Process;

class Parser
{
    /**
     * Parses the malware ontology using a Python script.
     *
     * @return string The path to the generated malware ontology file.
     * @throws ScriptFailedException If the script fails to update the malware ontology.
     */
    public static function parseMalware(): string
    {
        //todo change command based on server config (python3 vs python vs ...)
        //todo pip install owlready2
        //todo pip install mitreattack-python
        $command = 'python ' . base_path('app/bin/ontologyMappers/malware-attck.py') . ' ' . escapeshellarg(base_path('app/bin/malware/malwareTemplate.owl')) . ' ' . escapeshellarg(base_path('app/bin/malware/output/malware.owl'));
        $output = [];
        $return_var = -1;
        exec($command, $output, $return_var);

        if ($return_var == 0) {
            return base_path('app/bin/output/malware.owl');
        }
        throw new ScriptFailedException('Failed to update Malware!', [
            'response' => [
                'error' => true,
                'script_name' => 'malware-attck.py',
                'ontology' => 'malware',
                'return_var' => $return_var,
            ],
        ]);
    }

    public static function createOntologyConfig($owlFileName)
    {
        $path = app_path() . '\bin';
        //todo change python path based on server config
        //If you manage to change this function to work without absolute path for python, feel free to
        $command = ' C:\Users\mvrbo\AppData\Local\Programs\Python\Python312\python.exe createOntologyConfig.py ' . $owlFileName;

        $result = Process::path($path)->run($command);
        return $result->successful();
        /* dd(
            $result->successful(),
            $result->failed(),
            $result->exitCode(),
            $result->output(),
            $result->errorOutput()
        ); */
    }
}
