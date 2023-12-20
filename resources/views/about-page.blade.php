@extends('components.layouts.app')

@section('content')
    <div class="flex my-5 px-5 mx-auto">
        <div class="shadow-lg mb-5 p-5 xl:container mx-auto block w-full px-5">
            <div class="pt-5">
                <h2 class="text-3xl font-bold mb-2">About project</h2>
            </div>

            <hr>

            <div class="p-3">
                <p> This project was created in order to provide a structured view into different ontologies
                    and to keep itself expandable.
                </p>
            </div>

            <div class="p-3">
                <p> <span class="font-bold">Project repository:</span>
                    <a href="https://github.com/Timovy-projekt-01/Demo"
                        class="font-mono text-blue-500 hover:underline underline-offset-2 break-all" target="_blank">
                        https://github.com/Timovy-projekt-01/Demo
                    </a>
                </p>
            </div>

            <div class="mt-16">
                <h2 class="text-3xl font-bold mb-2">Documentation</h2>
            </div>

            <hr>

            <div class="p-3 w-11/12">
                <h4 class="text-xl font-bold mb-2 underline decoration-1 mt-2">Data sources</h4>
                <p class="p-2 text-justify">
                    Currently the Security domain ontology browser provides data from
                    <span class="font-medium">MITRE ATT&CK</span> (Adversarial Tactics, Techniques and Common Knowledge)
                    database alongside with data from
                    <span class="font-medium">CVE</span> (Common Vulnerabilities and Exposures) database
                    and <span class="font-medium">hybrid analysis</span> created by Bc. Lukáš Hurtiš within his
                    <a
                        href="https://opac.crzp.sk/?fn=detailBiblioFormChildCK36V&sid=2D0B46080A5AC858A715B81AE978&seo=CRZP-detail-kniha"
                        target="_blank">
                        bachelor thesis
                    </a>
                    in which he addressed the issue of storing and sharing data from dynamic malware analysis.
                    To create the dynamic analysis he used the open source tool Cuckoo Sandbox.
                    The results of the analysis were stored in an ontology, which is based on the MAEC security standard.
                </p>
                <p class="p-2 text-justify">
                    In the future, the intention is to extend the ontology model with additional data from different security domains.
                </p>

                <h4 class="text-xl font-bold mb-2 underline decoration-1 mt-10">Search</h4>
                <p class="p-2 text-justify">
                    You can easily start browsing just by typing a name of the malware, technique, tactic, group
                    or a software you are searching for in the searchbar.
                </p>

                <h4 class="text-xl font-bold mb-2 underline decoration-1 mt-10">Search history</h4>
                <p class="p-2 text-justify">
                    You can also easily browse through the entities you have already explored. These are being stored in
                    the search history panel on the side. By clicking on any of the entities you can get back to exploring
                    the entity.
                </p>
            </div>
        </div>
    </div>

@endsection
