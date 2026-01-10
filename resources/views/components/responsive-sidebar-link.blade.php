@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs bg-indigo-50 text-gray-700 font-bold'
            : 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs text-slate-500 hover:bg-gray-50 hover:text-slate-800 font-bold transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
   
