<div class="w-full">
    <div class="" >
        <div wire:loading class="mx-auto z-30 fixed  flex justify-center items-center h-screen w-screen opacity-70 ">
            <svg aria-hidden="true" class="absolute z- w-32 h-32  animate-spin text-gray-400 fill-gray-100"
                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                    fill="currentColor" />
                <path
                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                    fill="currentFill" />
            </svg>
        </div>
    </div>
    @if ($malware != null)
        <div class="shadow-lg my-5 p-5  mx-auto" wire:loading.class="opacity-20">

            {{-- ------------------------------------------------ --}}
            {{--              FIRST PART (name, crated, aliased) --}}
            {{-- ------------------------------------------------ --}}
            <div>
                <h2 class="text-4xl font-bold mb-2">{{ $malware['hasName'] }}</h2>
                <h5 class="text-slate-500 font-mono">{{ $malware['hasId'] }}</h5>
                <h3 class="text-xl font-semibold mb-2">{{ $malware['hasAliases'] ?? '' }}</h3>
                <h4 class="text-gray-600">Created: {{ $malware['wasCreated'] }} | Modified:
                    {{ $malware['wasLastModified'] }}</h4>
                <hr class="my-5  border-b-0.5 border-black">
            </div>

            {{-- ------------------------------------------------ --}}
            {{--              SECOND PART (url, contributors...) --}}
            {{-- ------------------------------------------------ --}}
            <div>
                @component('components.entityComponents.simple-property', [
                    'property' => $malware['type'] ?? null,
                    'label' => 'Type',
                ])
                @endcomponent
                @component('components.entityComponents.simple-property', [
                    'property' => $malware['hasDomain'] ?? null,
                    'label' => 'Domain',
                ])
                @endcomponent
                @component('components.entityComponents.simple-property', [
                    'property' => $malware['hasUrl'] ?? null,
                    'label' => 'URL',
                ])
                @endcomponent
                @component('components.entityComponents.simple-property', [
                    'property' => $malware['hasPlatforms'] ?? null,
                    'label' => 'Platforms',
                ])
                @endcomponent
                @component('components.entityComponents.simple-property', [
                    'property' => $malware['hasContributors'] ?? null,
                    'label' => 'Contributors',
                ])
                @endcomponent
                @component('components.entityComponents.simple-property', [
                    'property' => $malware['hasPermissionsRequired'] ?? null,
                    'label' => 'Permission Required',
                ])
                @endcomponent
                @component('components.entityComponents.simple-property', [
                    'property' => $malware['hasVersion'] ?? null,
                    'label' => 'Version',
                ])
                @endcomponent
                @component('components.entityComponents.simple-property', [
                    'property' => $malware['hasSystemRequirements'] ?? null,
                    'label' => 'System Requirements',
                ])
                @endcomponent
                @component('components.entityComponents.simple-property', [
                    'property' => $malware['hasDefensesBypassed'] ?? null,
                    'label' => 'Defenses Bypassed',
                ])
                @endcomponent
                @component('components.entityComponents.simple-property', [
                    'property' => $malware['hasDataSources'] ?? null,
                    'label' => 'Data Sources',
                ])
                @endcomponent
                <hr class="my-5  border-b-0.5 border-black">
            </div>


            {{-- ------------------------------------------------ --}}
            {{--              THIRD PART (description)           --}}
            {{-- ------------------------------------------------ --}}
            <div class="flex flex-col gap-3">
                @component('components.entityComponents.simple-property', [
                    'property' => $malware['hasDescription'] ?? null,
                    'label' => 'Description',
                ])
                @endcomponent
                @component('components.entityComponents.simple-property', [
                    'property' => $malware['hasDetection'] ?? null,
                    'label' => 'Detection',
                ])
                @endcomponent
                <hr class="my-5  border-b-0.5 border-black">
            </div>

            {{-- ------------------------------------------------ --}}
            {{--              COLAPSE LISTS                       --}}
            {{-- ------------------------------------------------ --}}
            @component('components.entityComponents.colapse-property-list', [
                'property' => $malware['usesTechnique'] ?? null,
                'label' => 'Techniques',
            ])
            @endcomponent
            @component('components.entityComponents.colapse-property-list', [
                'property' => $malware['mitigates'] ?? null,
                'label' => 'mitigates',
            ])
            @endcomponent
            @component('components.entityComponents.colapse-property-list', [
                'property' => $malware['usesSoftware'] ?? null,
                'label' => 'Softwares',
            ])
            @endcomponent
            @component('components.entityComponents.colapse-property-list', [
                'property' => $malware['usedIn'] ?? null,
                'label' => 'Used in',
            ])
            @endcomponent
            @component('components.entityComponents.colapse-property-list', [
                'property' => $malware['hasSubTechnique'] ?? null,
                'label' => 'Subtechniques',
            ])
            @endcomponent

            {{-- ------------------------------------------------ --}}
            {{--              COLAPSE STRINGS                      --}}
            {{-- ------------------------------------------------ --}}
            @component('components.entityComponents.colapse-property-string', [
                'property' => $malware['hasRelationshipCitations'] ?? null,
                'label' => 'Relationship Citations',
                'stringSeparation' => '),(',
            ])
            @endcomponent
            @component('components.entityComponents.colapse-property-string', [
                'property' => $malware['hasAssociatedGroupCitations'] ?? null,
                'label' => 'Associated Groups Citations',
                'stringSeparation' => ')(',
            ])
            @endcomponent
            @component('components.entityComponents.colapse-property-string', [
                'property' => $malware['hasAssociatedGroups'] ?? null,
                'label' => 'Associated Groups',
                'stringSeparation' => ',',
            ])
            @endcomponent
        </div>
    @endif
</div>
