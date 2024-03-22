<div>
    <div x-data="{
        clearHistory: function() {
            localStorage.removeItem('searchHistory');
            $wire.dispatch('clear-history');
        }
    }">
        <div class="flex flex-col ">
            <h3 class="text-xl font-bold text-center border-b-2 border-slate-600 pb-3">
                {{ __('app-labels.search history title') }}</h3>
            <div class="py-3 divide-y">
                @forelse ($history as $index => $entity)
                    <div class="transition-transform duration-500 ease-in-out hover:underline border-b py-2 cursor-pointer transform hover:translate-x-1"
                        wire:key="{{ $entity['uri'] }}"
                        wire:click.prevent="retrieveLoadedEntity('{{ $entity['uri'] }}')"
                        @click="() => { window.scrollTo({top: 0, behavior: 'smooth'}); }">
                        <p class="font-mono  ">
                            {{ $entity['title'] ?? $entity['displayId'] }}
                        </p>
                        <p class="font-mono text-sm text-gray-600 break-all">
                            {{ $entity['displayId'] }}
                        </p>
                    </div>
                @empty
                    <p class="text-center font-mono text-slate-400">
                        {{ __('app-labels.search history empty') }}
                    </p>
                @endforelse
            </div>
            <button
                class="text-sm text-center bg-transparent hover:bg-blue-500
                           text-blue-700 font-semibold hover:text-white py-1 px-2 border
                           border-blue-500 hover:border-transparent rounded"
                @click="clearHistory">
                {{ __('app-labels.search history clear button') }}
            </button>
        </div>
    </div>
</div>

@script
    <script>
        let history = JSON.parse(localStorage.getItem('searchHistory')) || [];
        console.log("PIPIK: ", history, $wire);
        $wire.dispatch('update-history', {
            history: history
        });
    </script>
@endscript
