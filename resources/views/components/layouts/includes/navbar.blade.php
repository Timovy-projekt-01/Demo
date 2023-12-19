<nav class="bg-gray-900 border-gray-200 p-3">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto">
        <a href="" class="flex items-center gap-5">
            <img src="/stu_logo4.png" class="h-24 p-2 rounded-3xl sm:hidden md:block" alt="upb eshop logo" />
            <span class="self-center text-2xl font-semibold whitespace-nowrap text-white hidden sm:block">
                Security domain ontology browser
            </span>
        </a>
        <button data-collapse-toggle="navbar-default" type="button" id="toggleButton"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500
                        rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2
                        focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-controls="navbar-default" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>
        <div class="hidden w-full md:flex md:w-auto md:mr-5" id="navbar-default">
            <span class="self-center text-base p-4 font-semibold whitespace-nowrap text-white sm:hidden">
                Security domain ontology browser
            </span>
            <ul
                class="font-medium flex flex-col p-4 md:p-0rounded-lg md:flex-row
                        md:space-x-8 md:mt-0 md:border-0 bg-gray-900 dark:border-gray-700">
                <li>
                    <a href=""
                       class="block py-2 pl-3 pr-4  rounded
                             md:border-0 md:hover:text-blue-700 md:p-0 text-white
                             md:dark:hover:text-blue-500
                             md:dark:hover:bg-transparent">About</a>
                </li>
            </ul>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.getElementById('toggleButton');
            const navLinks = document.getElementById('navbar-default');

            toggleButton.addEventListener('click', function () {
                navLinks.classList.toggle('hidden');
            });
        });
    </script>

</nav>

