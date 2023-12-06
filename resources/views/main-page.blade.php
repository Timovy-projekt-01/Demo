@extends('components.layouts.app')

@section('content')
    <div class="flex my-5 mx-auto">
        <div class="xl:container mx-auto flex w-full px-5 gap-5">

            <div class="basis-2/12 py-5 px-3 shadow-lg h-fit">
                <livewire-search-history />
            </div>
            <div class="basis-4/5 w-full flex flex-col ">
                <livewire-search-bar />
                <livewire-render-entity />
            </div>

        </div>
    </div>
    @livewireScripts
@endsection
