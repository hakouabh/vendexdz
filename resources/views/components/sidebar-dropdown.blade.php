@props(['title', 'icon' => null, 'open' => false])

@php
    // نضمن انو يفتح إذا عندو open=true
    $isOpen = $open ? 'true' : 'false';
@endphp

<div x-data="{ open: {{ $isOpen }} }" class="w-full">
    <button @click="open = !open"
        class="flex items-center w-full px-3.5 py-2 group hover:bg-blue-100 bg-blue-50 rounded-md">
        <span class="flex items-center justify-center">
            {!! $icon !!}
        </span>
        <span class="pl-4 pr-5">{{ $title }}</span>
        <svg :class="{ 'rotate-180': open }"
            class="ml-auto h-5 w-5 transform transition-transform duration-200"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-collapse class="mt-2 space-y-1 pl-8">
        {{ $slot }}
    </div>
</div>
