<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@vite('resources/css/app.css')
@vite('resources/js/app.js')
<link rel="stylesheet" href="{{ mix('css/app.css') }}">
<title>{{ $title ?? 'Page Title' }}</title>
{{-- @livewireStyles
@livewireScripts --}}
@yield('additional_head')
