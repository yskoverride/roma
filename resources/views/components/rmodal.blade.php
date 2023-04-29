<div x-data="{ open: @entangle($attributes->wire('model')) }" x-show="open" x-on:close-modal.window="open = false" x-on:keydown.escape.window="open = false" class="fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div style="display: none !important" x-show="open" class="fixed inset-0 transition-opacity" x-on:click="open = false" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div style="display: none !important" x-show="open" class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            {{ $slot }}
        </div>
    </div>
</div>
