<div class="w-3/4">

    <h1 class="flex items-center text-5xl font-extrabold text-gray-900">Pridanie/aktualizácia<span
            class="bg-blue-100 text-blue-800 text-2xl font-semibold me-2 px-2.5 py-0.5 rounded ms-2">ontológie</span>
    </h1>
    <form method="POST" wire:submit.prevent="uploadFile">
        @csrf
        <div class="flex items-center justify-center w-1/2 m-auto">
            <label for="dropzone-file"
                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 ">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 "><span class="font-semibold">Kliknutím nahraj.</span> alebo
                        pretiahni súbor</p>
                    <p class="text-xs text-gray-500 ">RDF, OWL</p>
                </div>
                <input name="file" id="dropzone-file" type="file" class="hidden" wire:model="ontologyFile"/>
            </label>
        </div>
        <div class="text-red-500">{{ $error ?? null }}</div>
        <button>Nahrat</button>
    </form>


{{-- UPLOAD AND PARSE FORM --}}
    <div class="bg-slate-200 my-10">
        <div class="text-center mb-2">
            <h1 class="text-2xl font-bold">Upload and Create Config for Ontology</h1>
        </div>
        <form wire:submit.prevent="createOwlConfig" class="max-w-md mx-auto">
            @csrf
            <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false"
                x-on:livewire-upload-error="uploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">
                <div class="mb-4 space-y-2">
                    <label for="owlFile" class="text-gray-700 text-sm font-bold">Select .owl File:</label>
                    <input wire:model="ontologyFile" type="file" name="owlFile" id="owlFile" accept=".owl"
                        class="appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-6">
                    <button type="submit"
                        class="bg-blue-500 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Upload File
                    </button>
                </div>
                <div x-show="uploading">
                    <progress max="100" x-bind:value="progress"></progress>
                </div>
            </div>
        </form>
    </div>

</div>