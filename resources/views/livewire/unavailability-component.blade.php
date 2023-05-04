<div class="p-4 ml-6 bg-white rounded">
    <form wire:submit.prevent="saveLunchBreak" class="space-y-4">
        <div>
            <label for="lunchBreak" class="block text-sm font-medium mt-4 mb-6">Lunch Break</label>
            <div class="mt-1 flex space-x-4">
                <select id="lunchBreakStart" wire:model="lunchBreak.start" class="block w-64 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    @for ($hour = 0; $hour <= 23; $hour++)
                        @for ($minute = 0; $minute <= 45; $minute += 30)
                            <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}">{{ sprintf('%02d:%02d', $hour, $minute) }}</option>
                        @endfor
                    @endfor
                </select>
                <span class="mt-1 text-gray-800">to</span>
                <select id="lunchBreakEnd" wire:model="lunchBreak.end" class="block w-64 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    @for ($hour = 0; $hour <= 23; $hour++)
                        @for ($minute = 0; $minute <= 45; $minute += 30)
                            <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}">{{ sprintf('%02d:%02d', $hour, $minute) }}</option>
                        @endfor
                    @endfor
                </select>
            </div>
            @error('lunchBreak.start') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            @error('lunchBreak.end') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Save Unavailability
            </button>
        </div>
    </form>
</div>