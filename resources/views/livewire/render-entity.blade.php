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
                    <h5 class="text-slate-500 font-mono">{{ $entity['hasId'] }}</h5>
                    @unset($entity['hasId'])
                @endif

                @if (isset($entity['hasAliases']))
                    <h3 class="text-xl font-semibold mb-2">{{ $entity['hasAliases'] }}</h3>
                    @unset($entity['hasAliases'])
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
                        <x-entityComponents.colapse-property-list :property="$value" :label="$key" />
                    @endif
                @endforeach
            </div>
            {{-- <hr class="my-5  border-b-0.5 border-black"> --}}
        </div>
        @endif
        {{-- @assets
        @vite(['resources/js/list-pagination.js'])
        @endassets --}}
        @script
        <script>
            $wire.on('newSearch', (entity) => {
                updateSearchHistory(entity);
                let history = JSON.parse(localStorage.getItem('searchHistory'));
                $wire.dispatch('add-to-history', { history: history })
            });

            function updateSearchHistory(newSearch) {
                let history = JSON.parse(localStorage.getItem('searchHistory')) || [];
                history.push(newSearch[0]);
                localStorage.setItem('searchHistory', JSON.stringify(history));

            }
        </script>
        @endscript
</div>

