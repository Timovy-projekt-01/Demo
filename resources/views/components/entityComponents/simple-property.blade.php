{{--
Component for displaying properties that has only one or very few items,
Therefore we have foreach if it has more than one item. but basically if else
statement is identical. We also check if its URL so its clickable.
--}}
@if (isset($property))
    <div class="flex gap-3">
        <p class="font-bold">{{ $label }}:</p>
        @if (is_array($property))
            <div class="flex gap-3">
                @foreach ($property as $item)
                    @if (filter_var($item, FILTER_VALIDATE_URL))
                        <a href="{{ $item }}" class="font-mono text-blue-500 hover:underline underline-offset-2" target="_blank">{{ $item }}</a>
                    @else
                        <p class="font-mono text-gray-600">{{ $item }}</p>
                    @endif
                @endforeach
            </div>
        @else
            @if (filter_var($property, FILTER_VALIDATE_URL))
                <a href="{{ $property }}" class="font-mono text-blue-500 hover:underline underline-offset-2" target="_blank">{{ $property }}</a>
            @else
                <p class="font-mono text-gray-600">{{ $property }}</p>
            @endif
        @endif
    </div>
@endif
