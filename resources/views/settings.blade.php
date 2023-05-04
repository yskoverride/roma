<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8">
        <x-heading>Settings</x-heading>

        <div class="py-6" x-data="{ tab: 'services' }">
            <div class="max-w-7xl mx-auto">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <!-- Tab buttons -->
                    <div class="hidden sm:block">
                        <nav class="flex space-x-4" aria-label="Tabs">
                            <button @click="tab = 'services'" class="rounded-md px-3 py-2 text-sm font-medium" :class="{'bg-indigo-100 text-indigo-700': tab === 'services', 'text-gray-500 hover:text-gray-700': tab !== 'services'}">Services</button>
                            <button @click="tab = 'my_services'" class="rounded-md px-3 py-2 text-sm font-medium" :class="{'bg-indigo-100 text-indigo-700': tab === 'my_services', 'text-gray-500 hover:text-gray-700': tab !== 'my_services'}">My Services</button>
                            <button @click="tab = 'schedules'" class="rounded-md px-3 py-2 text-sm font-medium" :class="{'bg-indigo-100 text-indigo-700': tab === 'schedules', 'text-gray-500 hover:text-gray-700': tab !== 'schedules'}">My Weekly Schedule</button>
                            <button @click="tab = 'unavailabilities'" class="rounded-md px-3 py-2 text-sm font-medium" :class="{'bg-indigo-100 text-indigo-700': tab === 'unavailabilities', 'text-gray-500 hover:text-gray-700': tab !== 'unavailabilities'}">My Unavailabilities</button>
                        </nav>
                    </div>

                    <!-- Tab content -->
                    <div class="mt-4">
                        <div x-show="tab === 'services'" x-transition>
                            <livewire:service-component />
                        </div>
                        <div x-show="tab === 'my_services'" x-transition>
                            <livewire:my-services-component :user="$user"/>
                        </div>
                        <div x-show="tab === 'schedules'" x-transition>
                            <livewire:schedule-component :user="$user"/>
                        </div>
                        <div x-show="tab === 'unavailabilities'" x-transition>
                            <livewire:unavailability-component :user="$user"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
