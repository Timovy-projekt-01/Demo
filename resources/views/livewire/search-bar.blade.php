{{--
Dynamic search bar. When selected entity is clicked, it dispatches (emits) a custom event
to RenderEntity component. That component is then responsible for rendering the entity.
--}}
<div x-data="{isOpen: false}" class=" flex flex-col justify-center p-5 align-middle shadow-lg w-full">
    <div class="p-5 w-full px-4 py-2">
        <div  @click.away="isOpen = false" class="relative">
            <input @click="isOpen = true"  type="text" name="eventSearch" id="eventSearch" class="form-input rounded-md shadow-sm mt-1 block w-full"
                wire:model.live="searchTerm" wire:input="searchEntities" placeholder="{{__('app-labels.search bar placeholder')}}" />
            @if ($searchTerm != '')
                <ul x-show="isOpen"
                    class="divide-y absolute z-10 bg-white border border-gray-300 rounded-md
                            shadow-md w-full flex flex-col overflow-auto max-h-96">
                    @if (!empty($entitiesFromSearch))
                        @foreach ($entitiesFromSearch as $entity)
                            <li class="flex flex-col cursor-pointer hover:bg-gray-100 px-3 py-1"
                                wire:key="{{ $entity['uri'] }}"
                                wire:click.prevent="
                                clearSearch();
                                $dispatch('show-new-entity', { id: '{{ $entity['uri'] }}' });">
                                <p type="button" class="font-semibold text-base">
                                    {{ $entity['title'] }}
                                </p>
                                <span
                                    class="text-slate-500 font-mono  text-sm">{{ $entity['displayId'] }}</span>
                            </li>
                        @endforeach
                        <button class="p-1 hover:bg-slate-200 font-semibold bg-slate-100" wire:click="showMoreResults()">{{__('app-labels.search bar more')}}</button>
                    @else
                        <li class="text-slate-500 font-mono text-base m-2">{{__('app-labels.search bar no results')}}</li>
                    @endif
                </ul>
            @endif
        </div>
    </div>
</div>
