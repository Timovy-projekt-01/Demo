<div>
    <div x-data="{
        delHistory: function() {
            console.log('clearHistory');
            localStorage.removeItem('searchHistory');
            $wire.history = [];
            console.log($wire.history);
            $wire.dispatch('re-render');
        }
    }">
        <div class="flex flex-col ">
            <h3 class="text-xl font-bold text-center border-b-2 border-slate-600 pb-3">Search History</h3>
            <div class="py-3 divide-y">
                @forelse ($history as $index => $entity)
                    <div class="transition-transform duration-500 ease-in-out hover:underline border-b py-2 cursor-pointer transform hover:translate-x-1"
                        wire:key="{{ $entity[array_key_first($entity)] }}"
                        wire:click.prevent="$dispatch('show-entity', { id: '{{ $entity[array_key_first($entity)] }}'});"
                        @click="() => { window.scrollTo({top: 0, behavior: 'smooth'}); }">
                        <p class="font-mono  ">
                            {{ $entity['hasName'] ?? '' }}
                        </p>
                        <p class="font-mono text-sm text-gray-600 break-all">
                            {{ $entity[array_key_first($entity)] }}
                        </p>
                    </div>
                @empty
                    <p class="text-center font-mono text-slate-400">
                        Empty
                    </p>
                @endforelse
            </div>
            <div
                class="text-sm text-center bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-1 px-2 border border-blue-500 hover:border-transparent rounded">
                <button @click="delHistory">Clear History</button>
            </div>
        </div>
    </div>
    @script
        <script>
            let history = JSON.parse(localStorage.getItem('searchHistory')) || [];
            $wire.history = history;
            $wire.dispatch('re-render');
        </script>
    @endscript
</div>
