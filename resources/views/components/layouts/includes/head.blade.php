<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@vite('resources/css/app.css')
@vite('resources/js/app.js')
<link rel="stylesheet" href="{{ mix('css/app.css') }}">
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<title>{{ $title ?? 'Page Title' }}</title>
@livewireStyles
@livewireScripts
@yield('additional_head')
