<div x-data="{ isOpen: false }" x-on:click.away="isOpen = false" class="relative">
    <label id="listbox-label" class="block text-sm font-medium leading-6 text-gray-900">Patient</label>
    <div class="relative mt-2">
        <input 
            type="text" 
            placeholder="Search patients..."
            class="relative w-full cursor-default rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6"
            wire:model.debounce.300ms="search"
            x-on:click="isOpen = true"
        />

        
        @if (!empty($search))
            <ul x-show="isOpen" class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" tabindex="-1" role="listbox" aria-labelledby="listbox-label">
                @if (count($this->patients))
                    @foreach ($this->patients as $patient)
                    <li x-on:click="isOpen = false" class="text-gray-900 cursor-pointer select-none py-2 pl-3 pr-9" wire:click="selectPatient({{ $patient->id }})" id="patient-option-{{ $patient->id }}">
                        <span class="font-normal truncate">{{ $patient->name }}</span>
                        <span class="text-gray-500 ml-2 truncate">{{ $patient->phone }}</span>
                    </li>
                    @endforeach
                @endif
                <li @click="isOpen = false" wire:click="$toggle('showAddPatientModal')" role="option" class="text-green-800 font-bold cursor-pointer select-none py-2 px-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2 text-green-800">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                    </svg>
                    <span>Add Patient</span>
                </li>
            </ul>
        @endif
    </div>

    <!-- Reusable modal component -->
    <x-rmodal wire:model="showAddPatientModal">
        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
            Add Patient
        </h3>
        <div class="flex flex-col space-y-6">
            <x-input :wireModel="'state.name'" :label="'Name'" :type="'text'" :name="'name'" :id="'name'" :placeholder="'Jane Smith'" :error="$errors->first('state.name')" />
            <x-input :wireModel="'state.attendant_name'" :label="'Attendant Name'" :type="'text'" :name="'attendant_name'" :id="'attendant_name'" :placeholder="'Robin Smith'" :error="$errors->first('state.attendant_name')" />
            <x-input :wireModel="'state.phone'" :label="'Phone Number'" :type="'text'" :name="'phone'" :id="'phone'" :placeholder="'+9194614242812'" :error="$errors->first('state.phone')" />
            
            <div class="mt-5 sm:mt-6 flex flex-column justify-end">
                <button type="button" wire:click.prevent="addPatient" class="mr-4 rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Add Patient
                </button>
                <button @click="open = false" type="button" class="rounded-md bg-red-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                    Close
                </button>
            </div>
        </div>
    </x-rmodal>

</div>
