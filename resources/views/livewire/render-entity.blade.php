{{--
Livewire component to render an entire entity
--}}
<div class="w-full">
    <x-miscellaneous.loading-wheel />
    @if ($entity != null)
        <div class="flex flex-col shadow-lg my-5 p-5 divide-y divide-black mx-auto" wire:loading.class="opacity-20">

            <div class="py-5">
                <h2 class="text-4xl font-bold mb-2">{{ $entity['title'] ?? $entity['displayId']}}</h2>

                <h5 class="text-slate-500 font-mono">{{ $entity['displayId'] }}</h5>
            </div>

            @if (!empty($entity['builtin_object_properties']))
                <div class="py-5">
                    @foreach ($entity['builtin_object_properties'] as $key => $value)
                        <x-entityComponents.simple-property :property="$value" :label="$key" />
                    @endforeach
                </div>
            @endif

            @if (!empty($entity['data_properties']))
                <div class="py-5">
                    @foreach ($entity['data_properties'] as $key => $value)
                        <x-entityComponents.simple-property :property="$value" :label="$key" />
                    @endforeach
                </div>
            @endif

            @if (!empty($entity['object_properties']))
                <div class="py-5">
                    @foreach ($entity['object_properties'] as $key => $value)
                        <livewire:paginated-colapse-property-list :list="$value" :label="$key" :key="'unique_' . uniqid()" />
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    @script
        <script>
            $wire.on('newSearch', (entity) => {
                updateSearchHistory(entity)
            });

            function updateSearchHistory(newSearch) {
                let history = JSON.parse(localStorage.getItem('searchHistory')) || [];
                // Check if the entity already exists in the history before adding it
                const filteredArray = history.filter(element => {
                    return Object.values(element)[0] === Object.values(newSearch[0])[0];
                });
                // If it doesnt exist, add it local storage and dispatch the event to update the history in the component
                if (filteredArray.length === 0) {
                    history.push(newSearch[0]);
                    localStorage.setItem('searchHistory', JSON.stringify(history));
                    $wire.dispatch('update-history', {
                        history: history
                    })
                }
            }
        </script>
    @endscript

</div>
