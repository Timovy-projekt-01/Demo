<div class="w-4/5 mx-auto shadow-lg p-5 my-5">
    <form wire:submit="submit()" class="container flex flex-col items-center  justify-center my-5">
        @if ($message)
            <div
                class="w-full flex text-center text-xl justify-center align-content-center rounded border-gray-500 mt-3 mb-3 p-4 bg-green-600 text-white">
                {{ $message }}
            </div>
        @endif

        <button type="submit"
            class="mt-6 mb-10 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-4/12">Save</button>

        <h1 class="text-gray-800 m-auto border-b-2 border-slate-600 py-4 text-3xl font-bold text-center w-full mb-6">
            Ontology information
        </h1>


        @if ($content_single)
            <div class="bg-blue-100 w-full text-left p-5 rounded">
                @foreach ($content_single as $key => $value)
                    <div class="mb-6 grid gap-4 md:grid-cols-2 sm:grid-cols-1 w-full">
                        <label for="{{ $key }}"
                            class="block mb-2 text-sm underline text-gray-900 font-medium">{{ $key }}</label>
                        <input wire:model='content_single.{{ $key }}' type="text" id="{{ $key }}"
                            name="{{ $key }}" value="{{ $value }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 w-full" />
                    </div>
                @endforeach
            </div>
        @endif

        @if ($content_array)
            @foreach ($content_array as $key => $value)
                <h1
                    class="text-gray-800 m-auto border-b-2 border-slate-600 py-4 text-3xl font-bold text-center w-full mb-6 mt-4">
                    {{ $key }}
                </h1>
                @if (self::SEARCHABLE == $key)
                    <div class="mb-6 gap-0 bg-blue-100 w-full text-left p-5 rounded">
                        <input wire:model='searchable' type="text" id="{{ $key }}"
                            value="{{ $searchable }}"
                            class="mb-0 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-2.5 pt-2.5" />
                        <p class="mt-0 text-sm text-gray-500 ">Add entire IRI of property that will be used for
                            searching an entity</p>
                        <p class="mt-0 text-sm text-gray-500 ">Supported formats: x1,x2,x3,x4 (comma separated).</p>
                    </div>
                    @continue
                @endif
                <div class="mb-6 grid gap-4 md:grid-cols-2 sm:grid-cols-1 bg-blue-100 w-full text-left p-5 rounded">
                    @foreach ($value as $id => $val)
                        <label for="{{ $id }}"
                            class="block mb-2 text-sm font-medium text-gray-900">{{ $map[$id] }}</label>
                        <input wire:model='content_array.{{ $key }}.{{ $id }}' type="text"
                            id="{{ $id }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 " />
                    @endforeach
                </div>
            @endforeach
        @endif
    </form>
</div>
