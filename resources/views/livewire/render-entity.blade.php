{{--
Livewire component to render an entire entity
--}}
<div class="w-full">
    <x-miscellaneous.loading-wheel/>
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
            {{--              SECOND PART (url, contributors...)  --}}
            {{-- ------------------------------------------------ --}}
            <div>
                <x-entityComponents.simple-property :property="$malware['type'] ?? null" :label="'Type'" />
                <x-entityComponents.simple-property :property="$malware['hasDomain'] ?? null" :label="'Domain'" />
                <x-entityComponents.simple-property :property="$malware['hasUrl'] ?? null" :label="'URL'" />
                <x-entityComponents.simple-property :property="$malware['hasPlatforms'] ?? null" :label="'Platforms'" />
                <x-entityComponents.simple-property :property="$malware['hasContributors'] ?? null" :label="'Contributors'" />
                <x-entityComponents.simple-property :property="$malware['hasPermissionsRequired'] ?? null" :label="'Permission Required'" />
                <x-entityComponents.simple-property :property="$malware['hasVersion'] ?? null" :label="'Version'" />
                <x-entityComponents.simple-property :property="$malware['hasSystemRequirements'] ?? null" :label="'System Requirements'" />
                <x-entityComponents.simple-property :property="$malware['hasDefensesBypassed'] ?? null" :label="'Defenses Bypassed'" />
                <x-entityComponents.simple-property :property="$malware['hasDataSources'] ?? null" :label="'Data Sources'" />
                <x-entityComponents.simple-property :property="$malware['hasAssociatedGroups'] ?? null" :label="'Associated Groups'"/>
                <hr class="my-5  border-b-0.5 border-black">
            </div>


            {{-- ------------------------------------------------ --}}
            {{--              THIRD PART (description)           --}}
            {{-- ------------------------------------------------ --}}
            <div class="flex flex-col gap-3">
                <x-entityComponents.simple-property :property="$malware['hasDescription'] ?? null" :label="'Description'" />
                <x-entityComponents.simple-property :property="$malware['hasDetection'] ?? null" :label="'Detection'" />
                <hr class="my-5  border-b-0.5 border-black">
            </div>

            {{-- ------------------------------------------------ --}}
            {{--              COLAPSE LISTS                       --}}
            {{-- ------------------------------------------------ --}}
            <x-entityComponents.colapse-property-list :property="$malware['usesTechnique'] ?? null" :label="'Techniques'" />
            <x-entityComponents.colapse-property-list :property="$malware['mitigates'] ?? null" :label="'mitigates'" />
            <x-entityComponents.colapse-property-list :property="$malware['usesSoftware'] ?? null" :label="'Softwares'" />
            <x-entityComponents.colapse-property-list :property="$malware['hasSubTechnique'] ?? null" :label="'hasSubTechnique'" />
            <x-entityComponents.colapse-property-list :property="$malware['usedIn'] ?? null" :label="'Used in'" />
        </div>
        @endif
        {{-- @assets
        @vite(['resources/js/list-pagination.js'])
        @endassets --}}
        @script
        <script>
            $wire.on('newSearch', (malware) => {
                updateSearchHistory(malware);
                let history = JSON.parse(localStorage.getItem('searchHistory'));
                $wire.dispatch('add-to-history', { history: history })
            });

            function updateSearchHistory(newSearch) {
                let history = JSON.parse(localStorage.getItem('searchHistory')) || [];
                history.push(newSearch[0]);
                localStorage.setItem('searchHistory', JSON.stringify(history));

            }
        </script>
        @endscript
</div>
