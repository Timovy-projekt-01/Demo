<div>
    <div x-data="{
        delHistory: function() {
            console.log('clearHistory');
            localStorage.removeItem('searchHistory');
            $wire.history = [];
            console.log($wire.history);
            $wire.dispatch('re-render');
        }
    }" x-init="let history = JSON.parse(localStorage.getItem('searchHistory')) || [];
    console.log(history);
    $wire.history = history;">
        <div class="flex flex-col ">
            <h3 class="text-xl font-bold text-center border-b-2 border-slate-600 pb-3">Search History</h3>
            <div class="py-3 divide-y">
                @forelse ($history as $item)
                <div class="transition-transform duration-500 ease-in-out hover:underline border-b py-2 cursor-pointer transform hover:translate-x-1">
                    <p class="font-mono  ">
                        {{ $item['hasName'] }}
                    </p>
                    <p class="font-mono text-sm text-gray-600 break-all">
                        {{ $item['hasId'] }}
                    </p>
                </div>
                @empty
                <p>
                    No history
                </p>
                @endforelse
            </div>
            <div class="text-sm text-center bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-1 px-2 border border-blue-500 hover:border-transparent rounded">
                <button @click="delHistory">Clear History</button>
            </div>
        </div>
    </div>
    @script
        <script>
            Livewire.on('add-to-history', (data) => {
                $wire.history = data['history'];
                console.log($wire.history.length == data['history'].length);
                Livewire.dispatch('re-render');
            });

            Livewire.on('mountHistory', () => {
                console.log('mountHistory');
                let history = JSON.parse(localStorage.getItem('searchHistory')) || [];
                $wire.history = history;
            });
        </script>
    @endscript
</div>
