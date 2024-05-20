@extends('components.layouts.app')

@section('content')
    <div class="w-4/5 mx-auto">
        <div class="container flex flex-col items-center  justify-center my-5">
            @livewire('config-list')
        </div>
    </div>
@livewireScripts()
@endsection
