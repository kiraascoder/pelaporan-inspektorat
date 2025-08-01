{{-- resources/views/components/status-badge.blade.php --}}
@props(['status'])

@php
    $classes = match ($status) {
        'Pending' => 'bg-yellow-100 text-yellow-800',
        'Diterima' => 'bg-green-100 text-green-800',
        'Dalam_Investigasi' => 'bg-blue-100 text-blue-800',
        'Ditolak' => 'bg-red-100 text-red-800',
        'Selesai' => 'bg-gray-100 text-gray-800',
        default => 'bg-gray-100 text-gray-800',
    };

    $statusText = match ($status) {
        'Pending' => 'Pending',
        'Diterima' => 'Diterima',
        'Dalam_Investigasi' => 'Dalam Investigasi',
        'Ditolak' => 'Ditolak',
        'Selesai' => 'Selesai',
        default => $status,
    };
@endphp

<span
    {{ $attributes->merge(['class' => "inline-flex items-center px-2 py-1 rounded-full text-xs font-medium $classes"]) }}>
    {{ $statusText }}
</span>
