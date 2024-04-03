<nav class="bg-gray-900 border-gray-200 p-3">
    <div x-data="{ mobileNavisOpen: true}"
        class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto">
        <a wire:navigate href="/" class="flex items-center gap-5">
            <img src="/stu_logo4.png" class="h-24 p-2 rounded-3xl sm:hidden md:block" alt="upb eshop logo" />

            <span class="self-center text-2xl font-semibold whitespace-nowrap text-white hidden sm:block">
                Security domain ontology browser
            </span>
        </a>

        <button @click="mobileNavisOpen = !mobileNavisOpen"
                data-collapse-toggle="navbar-default" type="button" id="toggleButton"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500
                        rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2
                        focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-controls="navbar-default" aria-expanded="false">

            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>

        <div x-cloak x-show="mobileNavisOpen"
            class="w-full lg:flex lg:w-auto lg:mr-5" id="navbar-default">
            <span class="self-center text-base p-4 font-semibold whitespace-nowrap text-white sm:hidden">
                Security domain ontology browser
            </span>

            <ul class="font-medium flex flex-col p-4 md:p-0rounded-lg md:flex-row
                        md:space-x-8 md:mt-0 md:border-0 bg-gray-900 dark:border-gray-700">
                <li class="py-2">
                    <a  wire:navigate href="/about"
                       class="block px-5 pl-3 pr-4 rounded
                             md:border-0 md:hover:text-blue-500 md:p-0 text-white
                             md:dark:hover:text-blue-500
                             md:dark:hover:bg-transparent">
                        {{__('app-labels.about')}}
                    </a>
                </li>
                <li class="py-2">
                    <a  wire:navigate href="{{ route('update') }}"
                       class="block px-5 pl-3 pr-4 rounded
                             md:border-0 md:hover:text-blue-500 md:p-0 text-white
                             md:dark:hover:text-blue-500
                             md:dark:hover:bg-transparent">
                        {{__('app-labels.upload')}}
                    </a>
                </li>

                <li class="py-2">
                    <a  wire:navigate href="{{ route('login') }}"
                       class="block px-5 pl-3 pr-4 rounded
                             md:border-0 md:hover:text-blue-500 md:p-0 text-white
                             md:dark:hover:text-blue-500
                             md:dark:hover:bg-transparent">
                             Login
                        {{-- {{__('app-labels.upload')}} --}}
                    </a>
                </li>

                <li  x-data="{ langDropdownisOpen: false }">
                    <button @click="langDropdownisOpen = !langDropdownisOpen"
                        class="text-white font-semibold text-base pl-3 pr-4 py-2 text-center relative inline-flex items-center md:hover:text-blue-500 ">
                        {{ Config::get('languages')[App::getLocale()] }}

                        <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>

                    <div x-cloak x-show="langDropdownisOpen" @click.away="langDropdownisOpen = false"
                        class="z-50 absolute divide-y divide-gray-100 shadow w-26 dark:bg-gray-700">
                        <ul class="p-2 text-base font-semibold text-gray-700 dark:text-gray-200 bg-white">
                            @foreach (Config::get('languages') as $lang => $language)
                                @if ($lang != App::getLocale())
                                    <li>
                                        <a wire:navigate
                                           class="block pl-1 pr-4 py-2
                                           hover:bg-gray-100
                                           dark:hover:bg-gray-600
                                           dark:hover:text-white"
                                           href="{{ route('lang.switch', $lang) }}">
                                            {{$language}}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>


</nav>

