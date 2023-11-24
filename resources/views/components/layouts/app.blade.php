<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @include('components.layouts.includes.head')
    </head>

    <body>
        @include('components.layouts.includes.navbar')
        <div class="min-h-screen">
           @yield('content')
        </div>
        @include('components.layouts.includes.footer')
    </body>

</html>
