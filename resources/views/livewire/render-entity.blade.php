{{-- TODO finish for all properties --}}
{{-- TODO Pre entity typu T a M treba nejak dodatocne ziskat Mitigators, Uses Software, Mitigates, Used in tactic atd --}}
<div class="w-full">
    @if ($malware != null)
        <div class="shadow-lg my-5 p-5  mx-auto">

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
                @if (isset($malware['type']))
                    <div class="flex gap-3">
                        <h3 class="font-bold">Type: </h3>
                        @foreach ($malware['type'] as $type)
                            <h4 class="text-gray-600 font-mono" wire:key="{{ $type }}">{{ $type }} </h4>
                        @endforeach
                    </div>
                @endif
                @if (isset($malware['hasDomain']))
                    <div class="flex gap-3">
                        <p class="font-bold">Domain: </p>
                        <p class="font-mono">{{ $malware['hasDomain'] }}</p>
                    </div>
                @endif
                @if (isset($malware['hasUrl']))
                    <div class="flex gap-3">
                        <p class="font-bold">Original URL: </p>
                        <a class="text-blue-500 hover:underline font-mono"
                            href="{{ $malware['hasUrl'] }}">{{ $malware['hasUrl'] }}</a>
                    </div>
                @endif
                @if (isset($malware['hasPlatforms']))
                    <div class="flex gap-3">
                        <p class="font-bold">Platforms: </p>
                        <p class="font-mono">{{ $malware['hasPlatforms'] }}</p>
                    </div>
                @endif
                @if (isset($malware['hasContributors']))
                    <div class="flex gap-3">
                        <p class="font-bold">Contributors: </p>
                        <p class="font-mono">{{ $malware['hasContributors'] }}</p>
                    </div>
                @endif
                @if (isset($malware['hasDataSources']))
                    <div class="flex gap-3">
                        <p class="font-bold">Data Sources: </p>
                        <div>
                            @foreach (explode(', ', $malware['hasDataSources']) as $item)
                                <p class="font-mono">{{ $item }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif
                <hr class="my-5  border-b-0.5 border-black">
            </div>


            {{-- ------------------------------------------------ --}}
            {{--              THIRD PART (description)           --}}
            {{-- ------------------------------------------------ --}}
            <div class="flex flex-col gap-3">
                @if (isset($malware['hasDescription']))
                    <div class="flex flex-col">
                        <p class="font-bold">Description:</p>
                        <p class="">{{ $malware['hasDescription'] }}</p>
                    </div>
                @endif
                @if (isset($malware['hasDetection']))
                    <div class="flex flex-col">
                        <p class="font-bold">Detection:</p>
                        <p class="">{{ $malware['hasDetection'] }}</p>
                    </div>
                @endif
                <hr class="my-5  border-b-0.5 border-black">
            </div>

            {{-- ------------------------------------------------ --}}
            {{--              FOURTH PART  (dropdown menus)      --}}
            {{-- ------------------------------------------------ --}}
            <div>
                @component('components.entityComponents.property-list', ['malware' => $malware, 'listType' => 'usesTechnique'])
                @endcomponent
                @component('components.entityComponents.property-list', ['malware' => $malware, 'listType' => 'mitigates'])
                @endcomponent
                @component('components.entityComponents.property-list', ['malware' => $malware, 'listType' => 'usesSoftware'])
                @endcomponent
                @component('components.entityComponents.property-list', ['malware' => $malware, 'listType' => 'usedIn'])
                @endcomponent
                @if (isset($malware['hasRelationshipCitations']))
                    <h3 class="font-bold cursor-pointer" wire:click="toggleMenu('citation')">
                        Citations <span>{{ $menu['citation']['isOpen'] ? '▼' : '►' }}</span>
                    </h3>
                    @if ($menu['citation']['isOpen'])
                        <ul>
                            @foreach (explode('),(', $malware['hasRelationshipCitations']) as $citation)
                                @if ($citation)
                                    <li class="border-b py-2" wire:key="{{ $citation }}">
                                        <h4 class="text-gray-600">
                                            {{ trim(explode(':', $citation)[1] ?? '') }}</h4>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                @endif
            </div>
    @endif
</div>
