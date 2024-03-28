{{--
Component for displaying properties that has only one or very few items,
Therefore we have foreach if it has more than one item. but basically if else
statement is identical. We also check via regex if its URL so its clickable.
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
            <div class="flex-grow">
                @php
                    echo preg_replace_callback(
                        '/\[([^\]]+)\]\(([^)]+)\)|(\b(?:https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/i',
                        function($matches) {
                            $linkText = !empty($matches[2]) ? $matches[1] : $matches[3];
                            $url = !empty($matches[2]) ? $matches[2] : $matches[3];
                            return '<a class="font-mono text-blue-500 hover:underline underline-offset-2"
                                         target="_blank" href="' . $url . '">' . $linkText . '</a>';
                        }, $property
                    );
                @endphp
            </div>
        @endif
    </div>
@endif
