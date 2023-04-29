@props([
    'label',
    'type' => 'text',
    'name',
    'id',
    'placeholder',
    'error' => null,
    'wireModel' => null
])

<div class="relative mt-6">
    <label for="{{ $id }}" class="absolute -top-2 left-2 inline-block bg-white px-1 text-sm font-medium text-gray-900">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" class="pt-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 {{ $error ? 'ring-red-500 focus:ring-red-500' : 'focus:ring-2 focus:ring-inset focus:ring-indigo-600' }} sm:text-sm sm:leading-6" placeholder="{{ $placeholder }}" {{ $wireModel ? 'wire:model.defer='.$wireModel : '' }}>

    @if ($error)
        <div class="pt-1 left-2 bottom-[-20px] text-xs text-red-500">{{ $error }}</div>
    @endif
</div>
