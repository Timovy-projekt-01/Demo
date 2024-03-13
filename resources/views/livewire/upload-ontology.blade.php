<div class="w-3/4">
    <style>
        .file-upload-label {
            transition: background-color 1s ease;
        }
        .toggleContainer {
            position: relative;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            width: fit-content;
            border: 3px solid #343434;
            border-radius: 0.5rem;
            background: #343434;
            font-weight: bold;
            color: #343434;
            cursor: pointer;
        }
        .toggleContainer::before {
            content: '';
            position: absolute;
            width: 50%;
            height: 100%;
            left: 0%;
            border-radius:0.5rem;
            background: white;
            transition: all 0.3s;
        }
        .toggleCheckbox:checked + .toggleContainer::before {
            left: 50%;
        }
        .toggleContainer div {
            padding: 6px;
            text-align: center;
            z-index: 1;
        }
        .toggleCheckbox {
            display: none;
        }
        .toggleCheckbox:checked + .toggleContainer div:first-child{
            color: white;
            transition: color 0.3s;
        }
        .toggleCheckbox:checked + .toggleContainer div:last-child{
            color: #343434;
            transition: color 0.3s;
        }
        .toggleCheckbox + .toggleContainer div:first-child{
            color: #343434;
            transition: color 0.3s;
        }
        .toggleCheckbox + .toggleContainer div:last-child{
            color: white;
            transition: color 0.3s;
        }
    </style>
    <h1 class="text-3xl font-bold text-center border-b-2 border-slate-600 my-4 py-4 items-center text-gray-900">
        <input wire:change='action' type="checkbox" id="toggle" class="toggleCheckbox" />
        <label for="toggle" class='toggleContainer m-auto mb-4'>
          <div>{{__('upload-page.add')}}</div>   
          <div>{{__('upload-page.update')}}</div>
        </label>
        <span class="bg-blue-100 text-blue-800 text-2xl font-semibold me-2 px-2 py-0.5 rounded ms-2">
            {{__('upload-page.ontology')}}
        </span>
    </h1>

    <form method="POST" wire:submit.prevent="uploadFile">
        @csrf
        <div class="flex items-center justify-center sm:w-full lg:w-1/2 my-auto py-7 mx-auto">
            <label for="dropzone-file"
                class="file-upload-label @if ($ontologyFile) bg-green-500 @endif flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 ">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    @if ($ontologyFile)
                        <div class="rounded-md bg-gray-200 m-12 p-6">
                            <p class="mb-2 text-lg text-gray-700 font-bold ">{{ $ontologyFile->getClientOriginalName() }}</p>
                        </div>
                    @else
                        <svg class="w-8 h-8 mb-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                        </svg>
                        <p class="flex-auto text-center mb-2 text-sm text-gray-500 "><span class="font-semibold">{{__('upload-page.upload title 1')}}</span>
                            {{__('upload-page.upload title 2')}}
                        </p>
                        <p class="text-xs text-gray-500 ">RDF, OWL</p>
                    @endif
                </div>
                <input name="file" id="dropzone-file" type="file" class="hidden" wire:model="ontologyFile"/>
            </label>
        </div>
        <div class="text-red-500">{{ $error ?? null }}</div>
        <div class="text-red-500">
            @error('ontologyFile')
                {{ $message }}
            @enderror
        </div>
        @if ($ontologyFile)
            <div class="flex items-center justify-center w-1/2 m-auto">
                <button wire:loading.attr="disabled"
                        wire:loading.class="opacity-50"
                        class="bg-blue-500 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full mt-4"
                        >
                        {{__('upload-page.upload butt')}}
                </button>
            </div>
        @endif
    </form>
</div>
