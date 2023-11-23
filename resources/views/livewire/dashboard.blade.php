<div class="flex flex-col justify-center p-5 align-middle">
    <div class="p-5 w-full sm:w-1/2 px-4 py-2 ">
        <input type="text" name="eventSearch" id="eventSearch" class="form-input rounded-md shadow-sm mt-1 block"
            wire:model.live="filterItems" placeholder="Hľadať ..." />
        @if ($filterItems != '')
            <ul>
                @foreach ($entitiesFromSearch as $entity)
                    <li class="hover:underline underline-offset-4" wire:key="{{ $entity['entity']['value'] }}">
                        <a href="#" wire:click.prevent="showEntireEntity('{{ $entity['entity']['value'] }}')">
                            {{ $entity['value']['value'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    @if ($properties != null)
        <div>
            <ul>
                @foreach ($properties as $property)
                    <li class="hover:underline underline-offset-4 border-b py-4">
                        <h3 class="text-xl font-bold mb-2">{{ $property['property']['value'] }}</h3>
                        <h4 class="text-gray-600">{{ $property['value']['value'] }}</h4>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
