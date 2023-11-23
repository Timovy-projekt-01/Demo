<div class="flex justify-center">
    <div class="p-5 w-full sm:w-1/2 px-4 py-2 ">
        <input type="text" name="eventSearch" id="eventSearch" class="form-input rounded-md shadow-sm mt-1 block"
            wire:model.live="filterItems" placeholder="Hľadať ..." />
        @if ($filter != '%%')
            <ul>
                @foreach ($entitiesFromSearch as $entity)
                    <li class="hover:underline underline-offset-4">
                        <a href="#" wire:click="showEntireEntity('{{ $entity['entity']['value'] }}')">
                            {{ $entity['value']['value'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    @if ($properties != null)
            <ul>
                @foreach ($properties as $property)
                    <li class="hover:underline underline-offset-4">
                        <h3>{{ $property['property']['value'] }}</h3>
                        <h4>{{ $property['value']['value'] }}</h4>
                    </li>
                @endforeach
            </ul>
        @endif
</div>
