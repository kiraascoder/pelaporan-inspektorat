@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-6">
        <div class="max-w-2xl w-full bg-white p-8 rounded-lg shadow-md space-y-6">
            <div class="text-center">
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-primary-600">
                    <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM10 4.414L5 8.414V17h2v-4a1 1 0 011-1h4a1 1 0 011 1v4h2V8.414L10 4.414z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="mt-4 text-2xl font-bold text-gray-900">Daftar Akun Warga</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-primary-600 hover:underline">Masuk</a>
                </p>
            </div>

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}"
                            required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        @error('nama_lengkap')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- nik --}}
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
                        <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        @error('nik')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        @error('email')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- No Telepon --}}
                    <div>
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700">No Telepon</label>
                        <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon') }}" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        @error('no_telepon')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat (full width) --}}
                    <div class="sm:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="2" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        @error('password')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                            Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Daftar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
