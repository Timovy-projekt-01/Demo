{{--
Component for displaying properties that has only one or very few items,
Therefore we have foreach if it has more than one item. but basically if else
statement is identical. We also check if its URL so its clickable.
--}}
@if (isset($property))
    <div class="flex gap-3">
        <p class="font-bold">{{ $label }}:</p>
        @if (is_array($property))
            <div class="flex flex-wrap">
                @foreach ($property as $item)
                    @if (filter_var($item, FILTER_VALIDATE_URL))
                        <a href="{{ $item }}" class="font-mono text-blue-500 hover:underline underline-offset-2 break-all" target="_blank">{{ $item }}</a>
                    @else
                        <p class="font-mono text-gray-600 break-all">
                            {{ $item }}
                            @if(!$loop->last) &nbsp;|&nbsp; @endif</p>
                    @endif
                @endforeach
            </div>
        @else
            @if (filter_var($property, FILTER_VALIDATE_URL))
                <a href="{{ $property }}" class="font-mono text-blue-500 hover:underline underline-offset-2 break-all" target="_blank">{{ $property }}</a>
            @else
                <div class="flex-grow">
                @php
                    echo preg_replace_callback(
                        '/\[([^\]]+)\]\(([^)]+)\)/',
                        function($matches) {
                            return '<a class="font-mono text-blue-500 hover:underline underline-offset-2"
                                     target="_blank" href="' . $matches[2] . '">' . $matches[1] . '</a>';
                        }, $property
                    );
                @endphp
                </div>
            @endif
        @endif
    </div>
@endif
