<div>
    <h1>
        <h1 class="text-center font-bold text-white bg-green-900">
            Hello World
        </h1>
    </h1>
    <div class="flex justify-center items-center ">
        <button wire:click="fetchData" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Fetch Data
        </button>
    </div>
    <input type="text" wire:model.live="input">
    <p>P: {{$input}}</p>
    <div>
        @if ($results)
            <ul>
                @foreach ($results as $result)
                    <li>{{ $result['entity']['value'] }}</li>
                    <li>{{ $result['property']['value'] }}</li>
                    <li>{{ $result['value']['value'] }}</li>
                    <br>
                    <br>
                @endforeach
            </ul>
        @endif
    </div>
</div>
