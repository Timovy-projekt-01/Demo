<div class="w-3/4">
    
    <h1 class="flex items-center text-5xl font-extrabold text-gray-900">Pridanie/aktualizácia<span class="bg-blue-100 text-blue-800 text-2xl font-semibold me-2 px-2.5 py-0.5 rounded ms-2">ontológie</span></h1>
    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex items-center justify-center w-1/2 m-auto">
            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 ">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 "><span class="font-semibold">Kliknutím nahraj.</span> alebo pretiahni súbor</p>
                    <p class="text-xs text-gray-500 ">RDF, OWL</p>
                </div>
                <input name="file" id="dropzone-file" type="file" class="hidden" />
            </label>
        </div> 
        <div class="text-red-500">{{ $error ?? null }}</div>
        <button type="submit">Nahrat</button>
    </form>

</div>