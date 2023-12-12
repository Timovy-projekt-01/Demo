{{--
This component displays other entities that are always in array
and is expected to have many values. All values are clickable.
Pagination is therefore also included here.
--}}
<div x-data="{ open: false }">
    @if (isset($list))
        <div>

        </div>
        <h3 @click="open = !open;" class="font-bold text-lg cursor-pointer mb-2">
            {{ $label }} <span x-text="open ? '▼' : '►'" class="inline-block"></span>
        </h3>
        <ul x-show="open" @click="open = !open" class="list-none pl-4">
            @foreach ($currentList as $item)
                <li class="transition-transform duration-500 ease-in-out hover:underline border-b py-2 cursor-pointer transform hover:translate-x-1"
                    wire:key="{{ $item['id'] }}" wire:click="$dispatch('show-new-entity', { id: '{{ $item['id'] }}' })"
                    @click="() => { window.scrollTo({top: 0, behavior: 'smooth'}); }">
                    <h4>{{ $item['name'] }}</h4>
                    <span class="text-gray-500 font-mono text-sm">{{ $item['id'] }}</span>
                </li>
            @endforeach
        </ul>

        <div class=" mb-10">
            <nav x-show="open" class="pagination is-centered" role="navigation" aria-label="pagination">
                <button class="pagination-previous" wire:click="goToPrevPage()">Previous</button>
                <button class="pagination-next" wire:click="goToNextPage()">Next page</button>
                <ul class="pagination-list">
                    <li><button class="pagination-link" wire:click="goToFirstPage()">1</button></li>
                    <li><span class="pagination-ellipsis">&hellip;</span></li>
                    <li><a class="pagination-link is-current">{{ $currentPage }}</a></li>
                    <li><span class="pagination-ellipsis">&hellip;</span></li>
                    <li><button class="pagination-link" wire:click="goToLastPage()"> {{ $lastPage }}</button></li>
                </ul>
            </nav>
        </div>

    @endif
</div>
