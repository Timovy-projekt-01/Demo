<div x-data="{ open: false }">
    @if (isset($property))
        <h3 @click="open = !open;" class="font-bold text-lg cursor-pointer mb-2">
            {{ $label }} <span x-text="open ? '▼' : '►'" class="inline-block"></span>
        </h3>
        <ul x-show="open" @click="open = !open" class="list-none pl-4">
            @foreach ($property as $item)
                <li class="transition-transform duration-500 ease-in-out hover:underline border-b py-2 cursor-pointer transform hover:translate-x-1"
                    wire:key="{{ $item['id'] }}" wire:click="showEntireEntity('{{ $item['id'] }}')"
                    @click="() => { window.scrollTo({top: 0, behavior: 'smooth'}); }">
                    <h4>{{ $item['name'] }}</h4>
                    <span class="text-gray-500 font-mono text-sm">{{ $item['id'] }}</span>
                </li>
            @endforeach
        </ul>
    @endif
</div>

@assets
    @vite(['resources/js/list-pagination.js'])
@endassets

