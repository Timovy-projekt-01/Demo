<div class="w-3/4 shadow-lg p-5 my-5">
    <h1 class="text-gray-800 m-auto border-b-2 border-slate-600 py-4 text-3xl font-bold text-center">
        List of configs:
    </h1>

    <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-6 py-5">
        @foreach ($files as $config)
            <a href="{{ route('config-edit', ['config' => $config->id]) }}"
               class="block last:mb-6 max-w-sm p-6 border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 bg-blue-100 text-blue-800">
                <h5 class="mb-2 text-lg font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $config->name }}
                </h5>
                <p class="font-normal text-sm text-gray-700 dark:text-gray-400">{{ $config->updated_at }}</p>
            </a>
        @endforeach
    </div>
</div>
