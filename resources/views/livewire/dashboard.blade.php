<div>
    <div>
        <input type="text" name="eventSearch" id="eventSearch" class="form-input rounded-md shadow-sm mt-1 block w-1/2" wire:model.live="filterItems" placeholder="Hľadať ..."/>
        @if ($filter != "%%")
            <ul>
                @foreach ($items as $item)
                    <li>
                        {{ $item->name }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
