{{--
Livewire component to render an entire entity
--}}
<div class="w-full">
    <x-miscellaneous.loading-wheel />
    @if ($entity != null)
        <div class="flex flex-col shadow-lg my-5 p-5 divide-y divide-black mx-auto" wire:loading.class="opacity-20">

            {{-- ------------------------------------------------ --}}
            {{--              FIRST PART (name, crated, aliased) --}}
            {{-- ------------------------------------------------ --}}
            <div class="py-5">
                @if (isset($entity['hasName']))
                    <h2 class="text-4xl font-bold mb-2">{{ $entity['hasName'] }}</h2>
                    @unset($entity['hasName'])
                @endif

                @if (isset($entity['hasId']))
                    <h5 class="text-slate-500 font-mono">{{ $entity[array_key_first($entity)] }}</h5>
                    @unset($entity[array_key_first($entity)])
                @endif

            </div>

            <div class="py-5">
                {{-- Prints only simple properties with one or two strings --}}
                @foreach ($entity as $key => $value)
                    {{-- Checks if the $value is NOT associative array, if it is, it should be rendered as collapse list at the end --}}
                    @if (!(isset($value[0]) && is_array($value[0]) && !array_is_list($value[0])))
                        <x-entityComponents.simple-property :property="$value" :label="$key" />
                        @unset($entity[$key])
                    @endif
                @endforeach
            </div>

            <div class="py-5">
                {{-- Renders colapsable lists at the end --}}
                @foreach ($entity as $key => $value)
                    {{-- Checks if the $value is associative array
                        Shouldn't be neccesary as we unset all properties along the way but just to be sure --}}
                    @if (isset($value[0]) && is_array($value[0]) && !array_is_list($value[0]))
                        <livewire:paginated-colapse-property-list :list="$value" :label="$key"
                            :key="'unique_' . uniqid()" />
                    @endif
                @endforeach
            </div>
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
