<div x-data="{ open: false }">
    @if (isset($property))
        <h3 class="font-bold cursor-pointer font-bold text-lg cursor-pointer mb-2" @click="open = !open;">
            {{$label}} <span x-text="open ? '▼' : '►'"></span>
        </h3>
        <ul x-show="open">
            @foreach (explode($stringSeparation, $property) as $item)
                @if ($item)
                    <li class="border-b py-2" wire:key="{{ $item }}">
                        <h4 class="text-gray-600">
                            {{ isset(explode(':', $item)[1]) ? trim(explode(':', $item)[1]) : $item }}
                        </h4>
                    </li>
                @endif
            @endforeach
        </ul>
    @endif
</div>
