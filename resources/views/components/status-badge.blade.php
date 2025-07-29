@props(['status'])

@php
    $status = strtolower($status);
    $colorClasses = match ($status) {
        'aktif' => 'bg-green-100 text-green-800',
        'nonaktif', 'tidak aktif' => 'bg-red-100 text-red-800',
        'pending' => 'bg-yellow-100 text-yellow-800',
        'selesai' => 'bg-blue-100 text-blue-800',
        default => 'bg-gray-100 text-gray-800',
    };
@endphp

<span class="text-xs px-2 py-1 rounded-full font-medium {{ $colorClasses }}">
    {{ ucfirst($status) }}
</span>
