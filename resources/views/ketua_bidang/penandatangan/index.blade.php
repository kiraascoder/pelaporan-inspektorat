@extends('layouts.dashboard')

@section('title', 'Master Penandatangan')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Master Penandatangan</h1>
                <p class="text-gray-600">Kelola pejabat yang dapat menandatangani surat tugas</p>
            </div>
            <a href="{{ route('ketua_bidang.penandatangan.create') }}"
                class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700">
                Tambah Penandatangan
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Jabatan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Pangkat</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">NIP</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($penandatangan as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $item->nama }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $item->jabatan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $item->pangkat ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $item->nip ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if ($item->is_active)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm flex gap-3">
                                <a href="{{ route('ketua_bidang.penandatangan.edit', $item->penandatangan_id) }}"
                                    class="text-blue-600 hover:text-blue-800">Edit</a>

                                <form action="{{ route('ketua_bidang.penandatangan.destroy', $item->penandatangan_id) }}"
                                    method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                Belum ada data penandatangan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $penandatangan->links() }}
            </div>
        </div>
    </div>
@endsection
