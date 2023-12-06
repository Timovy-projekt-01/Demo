<div>
    <div x-data="{ delHistory: function() {
        console.log('clearHistory');
        localStorage.removeItem('searchHistory');
        $wire.history = [];
        console.log($wire.history);
        $wire.dispatch('re-render');
        }}"
        x-init="
            let history = JSON.parse(localStorage.getItem('searchHistory')) || [];
            console.log(history);
            $wire.history = history;">
        <button @click="delHistory">Clear History</button>
        @forelse ($history as $item)
            <p>
                {{ $item['hasName'] }}
            </p>
            <p>
                {{ $item['hasId'] }}
            </p>
        @empty
            <p>
                No history
            </p>
        @endforelse
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
