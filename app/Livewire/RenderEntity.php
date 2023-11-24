<?php

namespace App\Livewire;

use Livewire\Component;
use App\Ontologies;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;

class RenderEntity extends Component
{
    public $properties;
    private $sparql;
    public $malware;
    protected $propertyTypes = [
        'TYPE' => 'type',
        'DOMAIN' => 'hasDomain',
        'RANGE' => 'range',
        'MITIGATES' => 'mitigates',
        'HAS_DESCRIPTION' => 'hasDescription',
        'HAS_CONTRIBUTORS' => 'hasContributors',
        'HAS_DATA_SOURCES' => 'hasDataSources',
        'HAS_ID' => 'hasId',
        'HAS_NAME' => 'hasName',
        'HAS_PLATFORMS' => 'hasPlatforms',
        'HAS_RELATIONSHIP_CITATIONS' => 'hasRelationshipCitations',
        'HAS_PERMISSIONS_REQUIRED' => 'hasPermissionsRequired',
        'HAS_CAPEC_ID' => 'hasCapecId',
        'HAS_MTC_ID' => 'hasMtcId',
        'HAS_ALIASES' => 'hasAliases',
        'HAS_URL' => 'hasUrl',
        'HAS_VERSION' => 'hasVersion',
        'HAS_SYSTEM_REQUIREMENTS' => 'hasSystemRequirements',
        'HAS_MITIGATORS' => 'hasMitigators',
        'USES_SOFTWARE' => 'usesSoftware',
        'USES_TECHNIQUE' => 'usesTechnique',
        'IS_SUBTECHNIQUE' => 'isSubTechnique',
        'IS_SUBTECHNIQUE_OF' => 'isSubTechniqueOf',
        'WAS_CREATED' => 'wasCreated',
        'WAS_LAST_MODIFIED' => 'wasLastModified',
        'DATA_TYPE_PROPERTY' => 'DatatypeProperty',
        'FUNCTIONAL_PROPERTY' => 'FunctionalProperty',
        'HAS_DEFENSES_BY_PASSED' => 'hasDefensesBypassed',
        'HAS_ASSOCIATED_GROUPS' => 'hasAssociatedGroups',
        'HAS_ASSOCIATED_GROUPS_CITATIONS' => 'hasAssociatedGroupsCitations',
        'HAS_DOMAIN' => 'hasDomain',
        'HAS_DETECTION' => 'hasDetection',
        'HAS_SUBTECHNIQUE' => 'hasSubTechnique',
        'HAS_MITTRE_ATTACK_SIGNATURE' => 'hasMitreAttckSignature',
        'HAS_EXTRACTED_FILE' => 'hasExtractedFile',
        'HAS_CROWD_STRIKE_AI' => 'hasCrowdStrikeAi',
        'HAS_ATTCK_TECHNIQUE' => 'hasAttckTechnique',
        'HAS_CERTIFICATE' => 'hasCertificate',
        'HAS_FILE_METADATA' => 'hasFileMetadata',
        'HAS_PROCESS' => 'hasProcess',
        'HAS_SUBMISSION' => 'hasSubmission',
        'USED_IN_TACTIC' => 'usedInTactic',
    ];
    public $isOpen = false;

    public function toggleTechniques()
    {
        //dump($this->isOpen);
        $this->isOpen = !$this->isOpen;
    }
    public function boot(Ontologies\Malware\Queries $sparql)
    {
        $this->sparql = $sparql;
    }
    public function render()
    {
        return view('livewire.render-entity');
    }

    #[On('show-entity')]
    public function showEntireEntity($id)
    {
       // dump($id);
        $this->properties = $this->sparql->getMalwareProperties($id);
        $this->parseValues();
        $this->getNamesToIds(['usesTechnique', 'hasMitigators', 'usesSoftware']);
    }

    //Parses all entity properties so we remove things like uri, literal and
    //have assoc array with only property and its value
    private function parseValues()
    {

        //Fills the malware with all possible properties and its values.
        foreach ($this->propertyTypes as $type) {
            $this->malware[$type] = $this->extractPropertyVal($type);
        }

        // Map the values into assoc array
        $this->malware = array_map(function ($values) {
            return count($values) > 1 ? $values : $values[0] ?? null;
        }, $this->malware);
    }

    //Extracts value for propertyType
    private function extractPropertyVal($propertyType)
    {
        // Step 1: Filter elements with 'property' equal to 'propertyType' can be hasAliases, hasUrl etc.
        $filteredData = array_filter($this->properties, fn ($element) => $element['property']['value'] == $propertyType);

        // Step 2: Get the 'value' of 'value' for each filtered element
        $extractedValues = array_map(fn ($element) => $element['value']['value'], array_values($filteredData));
        return $extractedValues;
    }

    private function getNamesToIds(array $propertyTypes)
    {
        foreach ($propertyTypes as $propertyType) {
            if (!isset($this->malware[$propertyType])) {
                continue; // Skip if property type not found in malware
            }

            foreach ($this->malware[$propertyType] as $key => $propertyId) {
                $name = $this->sparql->getName($propertyId);
                // Replace the ID with a tuple (ID, Name) in the existing array
                $this->malware[$propertyType][$key] = ['id' => $propertyId, 'name' => $name];
            }
        }
    }

}
