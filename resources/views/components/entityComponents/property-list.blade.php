<div x-data="{ open: false, listType: '{{ $listType }}'}">
    @if ($malware[$listType] != null)
        <h3 @click="open = ! open" class="font-bold cursor-pointer">
            {{$listType}} <span x-text="open ? '▼' : '►'"></span>
        </h3>
        <ul x-show="open">
            @foreach ($malware[$listType] as $technique)
                <li class="hover:underline underline-offset-4 border-b py-2 cursor-pointer"
                    wire:key="{{ $technique['id'] }}" wire:click="showEntireEntity('{{ $technique['id'] }}')">
                    <h4 class="">{{ $technique['name'] }}</h4>
                    <span class="text-slate-500 font-mono text-sm">{{ $technique['id'] }}</span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
