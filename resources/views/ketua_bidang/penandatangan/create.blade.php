@extends('layouts.dashboard')

@section('title', 'Tambah Penandatangan')

@section('content')
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h1 class="text-xl font-bold text-gray-900 mb-6">Tambah Penandatangan</h1>

        <form action="{{ route('ketua_bidang.penandatangan.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" name="nama" value="{{ old('nama', $penandatangan->nama ?? '') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                <input type="text" name="jabatan" value="{{ old('jabatan', $penandatangan->jabatan ?? '') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pangkat</label>
                <input type="text" name="pangkat" value="{{ old('pangkat', $penandatangan->pangkat ?? '') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                <input type="text" name="nip" value="{{ old('nip', $penandatangan->nip ?? '') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Tanda Tangan (PNG)</label>
                <input type="file" name="ttd_image" accept="image/png"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white">

                @if (!empty($penandatangan?->ttd_image))
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $penandatangan->ttd_image) }}" alt="TTD"
                            class="h-20 object-contain border rounded p-2 bg-white">
                    </div>
                @endif

                @error('ttd_image')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg">
                Simpan
            </button>
        </form>
    </div>
@endsection
