@extends('layouts.dashboard')

@section('title', 'Tambah Penandatangan')

@section('content')
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h1 class="text-xl font-bold text-gray-900 mb-6">Tambah Penandatangan</h1>

        <form action="{{ route('ketua_bidang.penandatangan.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                <input type="text" name="jabatan" value="{{ old('jabatan') }}" required
                    placeholder="Contoh: Ketua Inspektorat" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pangkat</label>
                <input type="text" name="pangkat" value="{{ old('pangkat') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                <input type="text" name="nip" value="{{ old('nip') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                <input type="number" name="urutan" value="{{ old('urutan', 0) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                <label for="is_active" class="text-sm text-gray-700">Aktif</label>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('ketua_bidang.penandatangan.index') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg">Batal</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
