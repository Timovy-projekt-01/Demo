{{-- <div class="flex flex-col justify-center p-5 align-middle shadow-lg w-1/2">
    <div class="p-5 w-full  px-4 py-2 ">
        <input type="text" name="eventSearch" id="eventSearch" class="form-input rounded-md shadow-sm mt-1 block w-full"
            wire:model.live="searchTerm" placeholder="Hľadať ..." />
        @if ($searchTerm != '')
            <ul class="flex flex-col gap-3 mt-2 pl-1">
                @foreach ($entitiesFromSearch as $entity)
                    <li class="flex flex-col hover:underline underline-offset-4 cursor-pointer" wire:key="{{ $entity['entity']['value'] }}">
                        <p type="button" class="font-semibold"
                            wire:click.prevent="clearSearch(); $dispatch('show-entity', { id: '{{ $entity['entity']['value'] }}' });">
                            {{ $entity['value']['value'] }}
                        </p>
                        <span class="text-slate-500 font-mono text-base">{{ $entity['entity']['value'] }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div> --}}
<div class="flex flex-col justify-center p-5 align-middle shadow-lg w-1/2">
    <div class="p-5 w-full px-4 py-2 relative">
        <input type="text" name="eventSearch" id="eventSearch" class="form-input rounded-md shadow-sm mt-1 block w-full"
            wire:model.live="searchTerm" placeholder="Hľadať ..." />
        @if ($searchTerm != '')
            <ul
                class="divide-y absolute z-10 bg-white border border-gray-300 rounded-md shadow-md w-full flex flex-col  mt-2 p-3 overflow-auto max-h-60 ">
                @foreach ($entitiesFromSearch as $entity)
                    <li class="flex flex-col hover:underline underline-offset-4 cursor-pointer"
                        wire:key="{{ $entity['entity']['value'] }}"
                        wire:click.prevent="clearSearch(); $dispatch('show-entity', { id: '{{ $entity['entity']['value'] }}' });"
                        >
                        <p type="button" class="font-semibold">
                            {{ $entity['value']['value'] }}
                        </p>
                        <span class="text-slate-500 font-mono text-base mb-2">{{ $entity['entity']['value'] }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
