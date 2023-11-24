{{-- TODO finish for all properties --}}
{{-- TODO finish for all properties --}}
{{-- TODO finish for all properties --}}
{{-- TODO finish for all properties --}}
<div class="w-full">
    @if ($properties != null)
        <div class="shadow-lg my-5 p-5  mx-auto">
            <h2 class="text-2xl font-bold mb-2">{{ $malware['hasName'] }}</h2>
            <h3 class="text-xl font-semibold mb-2">{{ $malware['hasAliases'] }}</h3>
            <h4 class="text-gray-600">Created: {{ $malware['wasCreated'] }} | Modified: {{ $malware['wasLastModified'] }}
            </h4>

            <hr class="my-5">

            @if ($malware['type'] != null)
                <div class="flex">
                    <h3 class="font-bold">Type:</h3>
                    @foreach ($malware['type'] as $type)

                        <li class="hover:underline underline-offset-4 border-b py-4">
                            <h4 class="text-gray-600">{{ html_entity_decode($type)  }}</h4>
                        </li>
                    @endforeach
                </div>
            @endif
            @if ($malware['hasUrl'] != null)
                <div class="flex">
                    <p class="font-bold">Original URL:</p>
                    <p class="">{{ $malware['hasUrl'] }}</p>
                </div>
            @endif
            @if ($malware['hasPlatforms'] != null)
                <div class="flex">
                    <p class="font-bold">Platforms:</p>
                    <p class="">{{ $malware['hasPlatforms'] }}</p>
                </div>
            @endif
            @if ($malware['hasContributors'] != null)
                <div class="flex">
                    <p class="font-bold">Contributors:</p>
                    <p class="">{{ $malware['hasContributors'] }}</p>
                </div>
            @endif

            <hr class="my-5">

            @if ($malware['hasDescription'] != null)
                <div class="flex flex-col">
                    <p class="font-bold">Description</p>
                    <p class="">{{ $malware['hasDescription'] }}</p>
                </div>
            @endif

            <hr class="my-5">
            @if ($malware['usesTechnique'] != null)
                <h3 class="font-bold">Techniques:</h3>
                @foreach ($malware['usesTechnique'] as $technique)
                    <li class="hover:underline underline-offset-4 border-b py-2">
                        <h4 class="text-gray-600">{{ $technique }}</h4>
                    </li>
                @endforeach
            @endif

            <hr class="my-5">

            @if ($malware['hasRelationshipCitations'] != null)
                <div class="flex flex-col">
                    <p class="font-bold">Citations</p>
                    @foreach (explode(',', $malware['hasRelationshipCitations']) as $citation)
                        @if ($citation)
                            <li class="hover:underline underline-offset-4 border-b py-2">
                                <h4 class="text-gray-600">{{ trim(explode(':', $citation)[1] ?? '') }}</h4>
                            </li>
                        @endif
                    @endforeach
                </div>
            @endif
            {{-- <ul>
                @foreach ($properties as $property)
                    <li class="hover:underline underline-offset-4 border-b py-4">
                        <h3 class="text-xl font-bold mb-2">{{ $property['property']['value'] }}</h3>
                        <h4 class="text-gray-600">{{ $property['value']['value'] }}</h4>
                    </li>
                @endforeach
            </ul> --}}
        </div>
    @endif
</div>
