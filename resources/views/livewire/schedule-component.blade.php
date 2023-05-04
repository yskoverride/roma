<div>
    <form wire:submit.prevent="save" class="mt-12 ml-4">
        @foreach($weekSchedules as $index => $availability)
            <div wire:key="availability-{{ $index }}" class="mt-2">
                <div class="flex text-sm items-center justify-start mt-2 mb-4 pb-4 {{ $loop->last ? '' : 'border-b border-gray-200' }}">
                    <div class="flex-none basis-1/4 mr-8">
                    <label>{{ $availability['day'] }} - ({{ $availability['formatted_date'] }})</label>
                    </div>
                    <div class="flex-none basis-1/4">
                        @unless($availability['is_past'])
                            <button type="button" wire:click="toggleAvailability({{ $index }})" :aria-checked="{{ $availability['available'] ? 'true' : 'false' }}" class="{{ $availability['available'] ? 'bg-green-600' : 'bg-gray-200' }} relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2" role="switch">
                                <span class="sr-only">Use setting</span>
                                <span aria-hidden="true" class="{{ $availability['available'] ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition-transform duration-200 ease-in-out"></span>
                            </button>
                        @endunless
                    </div>
                    <div class="flex-grow ml-4 basis-1/2">
                        <div class="flex items-center space-x-4 {{ $availability['available'] && ! $availability['is_past'] ? '' : 'opacity-0 pointer-events-none' }}">
                            <select wire:model="weekSchedules.{{ $index }}.start" class="block w-32 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6" @if($availability['is_past']) disabled @endif>
                                @for ($hour = 0; $hour <= 23; $hour++)
                                    @for ($minute = 0; $minute <= 45; $minute += 30)
                                        <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}">{{ sprintf('%02d:%02d', $hour, $minute) }}</option>
                                    @endfor
                                @endfor
                            </select>
                            <span class="text-gray-800">to</span>
                            <select wire:model="weekSchedules.{{ $index }}.end" class="block w-32 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6" @if($availability['is_past']) disabled @endif>
                                @for ($hour = 0; $hour <= 23; $hour++)
                                    @for ($minute = 0; $minute <= 45; $minute += 30)
                                    <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}">{{ sprintf('%02d:%02d', $hour, $minute) }}</option>
                                    @endfor
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if(count($errors))
                <span class="text-red-500 text-xs">Make sure the start time is less than end time for all days</span>
        @endif

        <div class="mt-4 text-right">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Update Schedule
            </button>
        </div>
    </form>
</div>

