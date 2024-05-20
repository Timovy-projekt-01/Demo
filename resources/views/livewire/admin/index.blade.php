<div>
    <div>
        <a href="{{route('update')}}">Vlož súbor</a>
    </div>
    <div>
        @forelse ($files as $file)
            <div class="flex flex-row">
                <p>
                    {{$file->name}}
                </p>
                <button wire:click="delete">Vymaž</button>
            </div>
        @empty
            <p>Zatial ste nenahrali žiadne súbory</p>
        @endforelse
    </div>
</div>
