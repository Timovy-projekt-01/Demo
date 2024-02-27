<?php

namespace App\Ontologies\Handlers;
use App\Exceptions\ScriptFailedException;

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
        $command = 'python ' . base_path('app/bin/malware/attck.py') . ' ' . escapeshellarg(base_path('app/bin/malware/malwareTemplate.owl')) . ' ' . escapeshellarg(base_path('app/bin/malware/output/malware.owl'));
        $output = [];
        $return_var = -1;
        exec($command, $output, $return_var);

        if ($return_var == 0) {
            return base_path('app/bin/malware/output/malware.owl');
        }
        throw new ScriptFailedException('Failed to update Malware!', [
            'response' => [
                'error' => true,
                'script_name' => 'attck.py',
                'ontology' => 'malware',
                'return_var' => $return_var,
            ],
        ]);
    }

    public static function getPredicates()
    {
        $data = shell_exec("python " . app_path() . '\bin\malware\malwareOwlParser.py');
        $data = json_decode($data, true);
        return $data;
    }
}