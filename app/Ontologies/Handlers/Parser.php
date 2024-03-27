<?php

namespace App\Ontologies\Handlers;

use App\Exceptions\ScriptFailedException;
use App\Traits\Path;
use App\Traits\Script;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class Parser
{

    use Script;

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
        //todo cronjob to run this script every x hours/days
        return self::runPythonScript(base_path('app/bin/ontologyMappers/malware-attck.py'), [
            base_path('app/bin/malware/malwareTemplate.owl'),
            base_path('app/bin/malware/output/malware.owl')
            ],
            base_path('app/bin/malware/output/malware.owl')
        );
    }

    public static function createOntologyConfig($owlFileName)
    {
        return self::runPythonScript('createOntologyConfig.py', [$owlFileName]);
    }
}
