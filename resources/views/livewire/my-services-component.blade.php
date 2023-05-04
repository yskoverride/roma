<div>
    <form wire:submit.prevent="updateServices">
        <div class="mb-4 ml-4">
            <div class="mt-4">
                @foreach ($services as $service)
                    <div class="flex items-center py-4">
                        <input wire:model="selectedServices" id="service-{{ $service->id }}" type="checkbox" value="{{ $service->id }}" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="service-{{ $service->id }}" class="ml-2 block text-sm text-gray-900">
                            {{ $service->name }} ({{ $service->duration }} Mins)
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="text-sm bg-indigo-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Update Services
            </button>
        </div>

    </form>
</div>

