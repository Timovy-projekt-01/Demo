<div class="flex flex-col justify-center p-5 align-middle shadow-lg w-1/2">
    <div class="p-5 w-full  px-4 py-2 ">
        <input type="text" name="eventSearch" id="eventSearch" class="form-input rounded-md shadow-sm mt-1 block w-full"
            wire:model.live="searchTerm" placeholder="Hľadať ..." />
        @if ($searchTerm != '')
            <ul>
                @foreach ($entitiesFromSearch as $entity)
                    <li class="hover:underline underline-offset-4" wire:key="{{ $entity['entity']['value'] }}">
                        <p type="button" class="cursor-pointer"
                            wire:click.prevent="$dispatch('show-entity', { id: '{{ $entity['entity']['value'] }}' })">
                            {{ $entity['value']['value'] }}
                        </p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
