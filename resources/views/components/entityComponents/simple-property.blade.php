 @if (isset($property))
     <div class="flex gap-3">
         <p class="font-bold">{{ $label }}:</p>
         @if (is_array($property))
             <div class="flex gap-3">
                 @foreach ($property as $item)
                     <p class="font-mono text-gray-600">{{ $item }}</p>
                 @endforeach
             </div>
         @else
             <p class="font-mono text-gray-600">{{ $property }}</p>
         @endif
     </div>
 @endif
