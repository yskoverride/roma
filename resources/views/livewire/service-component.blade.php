<div class="py-12">
    <form wire:submit.prevent="store" class="mb-4">
        <input type="text" wire:model="name" class="text-sm border rounded-md px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500" placeholder="New service name">

        <div class="relative inline-block w-40 ml-2">
            <select wire:model="duration" class="text-sm block appearance-none w-full bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 pr-8 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-base">
                <option value="">Select duration</option>
                <option value="10">10 Mins</option>
                <option value="15">15 Mins</option>
                <option value="30">30 Mins</option>
                <option value="45">45 Mins</option>
                <option value="60">60 Mins</option>
                <option value="120">120 Mins</option>
            </select>
        </div>


        <button type="submit" class="text-sm bg-indigo-500 text-white px-4 py-2 rounded-md ml-2">Add Service</button>
    </form>

    @if ($errors->any())
        <div class="mt-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
            <p class="font-bold">Please correct the following errors to add new service:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li class="mt-1 text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <ul class="py-12">
        @foreach($services as $service)
            <li class="border-b py-2 text-sm">{{ $service->name }} - {{ $service->duration }} Mins</li>
        @endforeach
    </ul>
</div>
