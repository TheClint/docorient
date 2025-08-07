@props(['route' => '', 'label'])

@php
    $baseClass = 'bg-red-600 h-12 text-white px-6 py-3 rounded-lg shadow-md hover:bg-red-700 transition ease-in-out duration-300 transform hover:scale-105';
@endphp

@if ($route !== '')
    <a href="{{ $route }}" {{ $attributes->merge(['class' => $baseClass]) }}>
        {{ $label }}
    </a>
@else
    <button type="button" {{ $attributes->merge(['class' => $baseClass]) }}>
        {{ $label }}
    </button>
@endif
