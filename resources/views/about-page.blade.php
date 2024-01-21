@extends('components.layouts.app')

@section('content')
    <div class="flex my-5 px-5 mx-auto">
        <div class="shadow-lg mb-5 p-5 xl:container mx-auto block w-full px-5">
            <div class="pt-5">
                <h2 class="text-3xl font-bold mb-2">{{__('about-page.about project')}}</h2>
            </div>

            <hr>

            <div class="p-3">
                <p>{{__('about-page.project info')}}</p>
            </div>

            <div class="p-3">
                <p> <span class="font-bold"> {{__('about-page.project repository')}} </span>
                    <a href="https://github.com/Timovy-projekt-01/Demo"
                        class="font-mono text-blue-500 hover:underline underline-offset-2 break-all" target="_blank">
                        https://github.com/Timovy-projekt-01/Demo
                    </a>
                </p>
            </div>

            <div class="mt-16">
                <h2 class="text-3xl font-bold mb-2">{{__('about-page.documentation')}}</h2>
            </div>

            <hr>

            <div class="p-3 w-11/12">
                <h4 class="text-xl font-bold mb-2 underline decoration-1 mt-2">{{__('about-page.data sources title')}}</h4>
                <p class="p-2 text-justify">
                    {{__('about-page.data sources info pt1')}}
                    <span class="font-medium">MITRE ATT&CK</span> {{__('about-page.data sources info pt2')}}
                    <span class="font-medium">CVE</span> {{__('about-page.data sources info pt3')}}
                    <span class="font-medium">{{__('about-page.data sources info pt4')}}</span>
                    {{__('about-page.data sources info pt5')}}
                    <a
                        href="https://opac.crzp.sk/?fn=detailBiblioFormChildCK36V&sid=2D0B46080A5AC858A715B81AE978&seo=CRZP-detail-kniha"
                        target="_blank">
                        {{__('about-page.data sources info pt6')}}
                    </a>
                    {{__('about-page.data sources info pt7')}}
                </p>

                <p class="p-2 text-justify">
                    {{__('about-page.data sources info pt8')}}
                </p>

                <h4 class="text-xl font-bold mb-2 underline decoration-1 mt-10">{{__('about-page.search title')}}</h4>
                <p class="p-2 text-justify">
                    {{__('about-page.search info')}}
                </p>

                <h4 class="text-xl font-bold mb-2 underline decoration-1 mt-10">{{__('about-page.search history title')}}</h4>
                <p class="p-2 text-justify">
                    {{__('about-page.search history info')}}
                </p>
            </div>
        </div>
    </div>

@endsection
