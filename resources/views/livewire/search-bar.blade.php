{{--
Dynamic search bar. When selected entity is clicked, it dispatched (emits) a custom event
to RenderEntity component. This component is then responsible for rendering the entity.
--}}
<div class="flex flex-col justify-center p-5 align-middle shadow-lg w-full">
    <div class="p-5 w-full px-4 py-2 relative">
        <input type="text" name="eventSearch" id="eventSearch" class="form-input rounded-md shadow-sm mt-1 block w-full"
            wire:model.live="searchTerm" wire:input="searchEntities" placeholder="Hľadať ..." />
        @if ($searchTerm != '')
            <ul
                class="divide-y absolute z-10 bg-white border border-gray-300 rounded-md
                        shadow-md w-full flex flex-col  mt-2 overflow-auto max-h-60">
                @if (!empty($entitiesFromSearch))
                    @foreach ($entitiesFromSearch as $entity)
                        <li class="flex flex-col cursor-pointer hover:bg-gray-100 px-3 py-1"
                            wire:key="{{ $entity['entity']['value'] }}"
                            wire:click.prevent="
                            clearSearch();
                            $dispatch('show-entity', { id: '{{ $entity['entity']['value'] }}' });">
                            <p type="button" class="font-semibold">
                                {{ $entity['value']['value'] }}
                            </p>
                            <span
                                class="text-slate-500 font-mono text-base mb-2">{{ $entity['entity']['value'] }}</span>
                        </li>
                    @endforeach
                    <button class="p-2 hover:bg-slate-200" wire:click="showMoreResults()">Show more</button>
                @else
                    <li class="text-slate-500 font-mono text-base m-2">No results</li>
                @endif
            </ul>
        @endif
    </div>
</div>
