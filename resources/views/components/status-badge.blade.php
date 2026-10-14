@props(['status'])

@php
    $color = match ($status) {
        \App\Enums\TaskStatus::Todo       => 'bg-gray-100 text-gray-700 ring-gray-500/20',
        \App\Enums\TaskStatus::InProgress => 'bg-blue-50 text-blue-700 ring-blue-600/20',
        \App\Enums\TaskStatus::Done       => 'bg-green-50 text-green-700 ring-green-600/20',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {$color}"]) }}>
    {{ $status->label() }}
</span>