<div class="space-y-10 divide-y divide-gray-900/10">
  <div class="grid grid-cols-1 gap-x-8 gap-y-8 pt-10 md:grid-cols-3">
    <div class="px-4 sm:px-0">
      <h2 class="text-base font-semibold leading-7 text-gray-900">Create Bookings</h2>
      <p class="mt-1 text-sm leading-6 text-gray-600">Select the Open Slots for Doctor.</p>
    </div>

    <form wire:submit.prevent="createBooking" class="w-full bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
      @csrf
      <div class="px-4 py-6 sm:p-8">
        <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8">
          <!-- Select Service -->
          <div>
            <label for="service" class="block text-sm font-medium leading-6 text-gray-900">Services</label>
            <div class="mt-2">
              <select wire:model="state.service" name="service" class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                <option value="" selected>Select an option..</option>
                @foreach($services as $service)
                  <option value="{{ $service->id }}">{{ $service->name }} - {{ $service->duration }} mins</option>
                @endforeach
              </select>
            </div>
            
            @error('state.service')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Select Doctor -->
          <div class="{{ !$users->count() ? 'opacity-50 pointer-events-none' : '' }}">
            <label for="user" class="block text-sm font-medium leading-6 text-gray-900">Doctors</label>
            <div class="mt-2">
              <select wire:model="state.user" name="user" class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6" {{ !$users->count() ? 'disabled = "disabled"' : '' }}>
                <option value="" selected>Select an option..</option>
                @foreach($users as $user)
                  <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
              </select>
            </div>

            @error('state.user')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Select Slot -->
          <div class="{{ $users->isEmpty() ? 'opacity-50 pointer-events-none' : '' }}">
            <livewire:booking-calendar :service="$this->SelectedService" :user="$this->selectedUser" :key="optional($this->selectedUser)->id">
          
            @error('state.time')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          

          <!-- booking Confirmation -->
          @if($this->hasDetailsToBook)
            <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6 mt-6">
              <h3 class="text-base font-semibold leading-6 text-gray-900">Ready to Book</h3>
            </div>

            <div class="text-gray-600 font-bold mt-4 p-2">
              {{ $this->selectedService->name }} session ({{ $this->selectedService->duration }} mins) with {{ $this->selectedUser->name }} <br>
              on {{ $this->timeObject->format('D jS M Y') }}  at {{ $this->timeObject->format('g:i A') }}
            </div>
          @endif

          <!-- searchable patients -->
          <div class="mt-2 {{ $users->isEmpty() ? 'opacity-50 pointer-events-none' : '' }}">
            @livewire('searchable-patient-dropdown')

            @error('state.patient')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
          
        </div>
      </div>
      <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
        <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
      </div>
    </form>
  </div>
</div>
