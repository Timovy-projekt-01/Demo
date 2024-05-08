<div class="w-4/5 mx-auto">
    <form wire:submit="submit()" class="container flex flex-col items-center  justify-center my-5">
        @if ($message)
            <div class="w-full flex text-center text-xl justify-center align-content-center rounded-lg border-gray-500 mt-3 mb-3 p-4 border bg-gray-500 text-gray-200">
                {{ $message }}
            </div>
        @endif

        <button type="submit" class="mt-6 mb-6 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>

        @if ($content_single)
            @foreach ($content_single as $key => $value)
                <div class="mb-6 grid gap-4 md:grid-cols-2 sm:grid-cols-1">
                    <label for="{{$key}}" class="block mb-2 text-sm font-medium text-gray-900">{{$key}}</label>
                    <input wire:model='content_single.{{$key}}' type="text" id="{{$key}}" name="{{ $key }}" value="{{ $value }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 " />
                </div> 
            @endforeach
        @endif
        
        @if($content_array)
            @foreach ($content_array as $key => $value)
                <div class="text-xl mb-6 mt-4">{{ $key }}</div>
                @if (self::SEARCHABLE == $key)
                    <div class="mb-6 w-3/4 gap-0">
                        <input wire:model='content_array.{{$key}}' type="text" id="{{ $key }}" value="{{ implode(',', $value) }}" class="mb-0 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-2.5 pt-2.5" />
                        <p class="mt-0 text-sm text-gray-500 ">Supported formats: x1,x2,x3,x4 (comma separated).</p>
                    </div>
                    @continue
                @endif
                <div class="mb-6 grid gap-4 md:grid-cols-2 sm:grid-cols-1">
                    @foreach ($value as $name => $val)
                        <label for="{{ $name }}" class="block mb-2 text-sm font-medium text-gray-900">{{ $name }}</label>
                        <input wire:model='content_array.{{$key}}.{{$name}}' type="text" id="{{ $name }}" value="{{ $val }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 " />
                    @endforeach
                </div>
            @endforeach
        @endif
    </form>  
</div>
