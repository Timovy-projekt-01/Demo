@extends('components.layouts.app')

@section('content')
    <div class="w-4/5 mx-auto">
        <div class="container flex flex-col items-center  justify-center my-5">
            <livewire-search-bar />
            <div>

                <div x-data="{ open: false }">
                    <button @click="open = true">Show 1...</button>

                    <ul x-show="open" @click.outside="open = false">
                        <span>Opened!1</span>
                    </ul>
                </div>
                <div x-data="{ open: false }">
                    <button @click="open = true">Show 3...</button>

                    <ul x-show="open" @click.outside="open = false">
                        <span>Opened!2</span>
                    </ul>
                </div>
                <div x-data="{ open: false }">
                    <button @click="open = true">Show 3...</button>

                    <ul x-show="open" @click.outside="open = false">
                        <span>Opened!3</span>
                    </ul>
                </div>
            </div>
            <livewire-render-entity />
        </div>
    </div>
    @livewireScripts
@endsection
