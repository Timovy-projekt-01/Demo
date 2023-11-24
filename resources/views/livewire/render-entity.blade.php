{{-- TODO finish for all properties --}}
{{-- TODO Pre entity typu T a M treba nejak dodatocne ziskat Mitigators, Uses Software, Mitigates, Used in tactic atd --}}
<div class="w-full">
    @if ($properties != null)
        <div class="shadow-lg my-5 p-5  mx-auto">

            {{-- ------------------------------------------------ --}}
            {{--              FIRST PART (name, crated, aliased) --}}
            {{-- ------------------------------------------------ --}}
            <div>
                <h2 class="text-4xl font-bold mb-2">{{ $malware['hasName'] }}</h2>
                <h3 class="text-xl font-semibold mb-2">{{ $malware['hasAliases'] }}</h3>
                <h4 class="text-gray-600">Created: {{ $malware['wasCreated'] }} | Modified:
                    {{ $malware['wasLastModified'] }}</h4>
                <hr class="my-5  border-b-0.5 border-black">
            </div>

            {{-- ------------------------------------------------ --}}
            {{--              SECOND PART (url, contributors...) --}}
            {{-- ------------------------------------------------ --}}
            <div>
                @if ($malware['type'] != null)
                    <div class="flex gap-3">
                        <h3 class="font-bold">Type: </h3>
                        @foreach ($malware['type'] as $type)
                            <h4 class="text-gray-600 font-mono" wire:key="{{ $type }}">{{ $type }} </h4>
                        @endforeach
                    </div>
                @endif
                @if ($malware['hasDomain'] != null)
                    <div class="flex gap-3">
                        <p class="font-bold">Domain: </p>
                        <p class="font-mono">{{ $malware['hasDomain'] }}</p>
                    </div>
                @endif
                @if ($malware['hasUrl'] != null)
                    <div class="flex gap-3">
                        <p class="font-bold">Original URL: </p>
                        <a class="text-blue-500 hover:underline font-mono"
                            href="{{ $malware['hasUrl'] }}">{{ $malware['hasUrl'] }}</a>
                    </div>
                @endif
                @if ($malware['hasPlatforms'] != null)
                    <div class="flex gap-3">
                        <p class="font-bold">Platforms: </p>
                        <p class="font-mono">{{ $malware['hasPlatforms'] }}</p>
                    </div>
                @endif
                @if ($malware['hasContributors'] != null)
                    <div class="flex gap-3">
                        <p class="font-bold">Contributors: </p>
                        <p class="font-mono">{{ $malware['hasContributors'] }}</p>
                    </div>
                @endif
                @if ($malware['hasDataSources'] != null)
                    <p class="font-bold">Data Sources: </p>
                    @foreach (explode(', ', $malware['hasDataSources']) as $item)
                        <p class="font-mono">{{ $item }}</p>
                    @endforeach
                @endif
                <hr class="my-5  border-b-0.5 border-black">
            </div>


            {{-- ------------------------------------------------ --}}
            {{--              THIRD PART (description)           --}}
            {{-- ------------------------------------------------ --}}
            <div class="flex flex-col gap-3">
                @if ($malware['hasDescription'] != null)
                    <div class="flex flex-col">
                        <p class="font-bold">Description:</p>
                        <p class="">{{ $malware['hasDescription'] }}</p>
                    </div>
                @endif
                @if ($malware['hasDetection'] != null)
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
                @if ($malware['usesTechnique'] != null)
                    <h3 class="font-bold">Techniques:</h3>
                    <ul>
                        @foreach ($malware['usesTechnique'] as $technique)
                            <li class="hover:underline underline-offset-4 border-b py-2">
                                <h4 class="text-gray-600 cursor-pointer"
                                    wire:click="showEntireEntity('{{ $technique['id'] }}')">
                                    {{ $technique['name'] }}</h4>
                            </li>
                        @endforeach
                    </ul>
                @endif
                @if ($malware['usesSoftware'] != null)
                    <h3 class="font-bold">Techniques:</h3>
                    <ul>
                        @foreach ($malware['usesSoftware'] as $technique)
                            <li class="hover:underline underline-offset-4 border-b py-2">
                                <h4 class="text-gray-600 cursor-pointer"
                                    wire:click="showEntireEntity('{{ $technique['id'] }}')">
                                    {{ $technique['name'] }}</h4>
                            </li>
                        @endforeach
                    </ul>
                @endif
                @if ($malware['hasMitigators'] != null)
                    <h3 class="font-bold">Techniques:</h3>
                    <ul>
                        @foreach ($malware['hasMitigators'] as $technique)
                            <li class="hover:underline underline-offset-4 border-b py-2">
                                <h4 class="text-gray-600 cursor-pointer"
                                    wire:click="showEntireEntity('{{ $technique['id'] }}')">
                                    {{ $technique['name'] }}</h4>
                            </li>
                        @endforeach
                    </ul>
                @endif
                @if ($malware['usedInTactic'] != null)
                    <h3 class="font-bold">Techniques:</h3>
                    <ul>
                        @foreach ($malware['usedInTactic'] as $technique)
                            <li class="hover:underline underline-offset-4 border-b py-2">
                                <h4 class="text-gray-600 cursor-pointer"
                                    wire:click="showEntireEntity('{{ $technique['id'] }}')">
                                    {{ $technique['name'] }}</h4>
                            </li>
                        @endforeach
                    </ul>
                @endif
                @if ($malware['hasRelationshipCitations'] != null)
                    <p class="font-bold">Citations</p>
                    <ul>
                        @foreach (explode(',', $malware['hasRelationshipCitations']) as $citation)
                            @if ($citation)
                                <li class="border-b py-2" wire:key="{{ $citation }}">
                                    <h4 class="text-gray-600 cursor-pointer">
                                        {{ trim(explode(':', $citation)[1] ?? '') }}</h4>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>


            {{-- POKUS O COLLAPSABLE MENU --}}

            {{-- @if ($malware['usesTechnique'] != null)
                <h3 class="font-bold cursor-pointer" wire:click="toggleTechniques">
                    Techniques <span>{{ $isOpen ? '▼' : '►' }}</span>
                </h3>
                @if ($isOpen)
                    <ul>
                        @foreach ($malware['usesTechnique'] as $technique)
                            <li class="hover:underline underline-offset-4 border-b py-2"
                                wire:key="{{ $technique }}">
                                <h4 class="text-gray-600">{{ $technique }}</h4>
                            </li>
                        @endforeach
                    </ul>
                @endif
            @endif --}}
        </div>
    @endif
</div>
