@extends('layouts.app')

@section('title', 'Edit User - Panel Admin')
@section('header-title', 'Manajemen Pengguna Sistem')

@section('content')

<div class="max-w-xl bg-white rounded-lg shadow-sm border border-gray-200 p-4">

    <form action="{{ route('admin.user.update', $user->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        {{-- NAMA LENGKAP --}}
        <div class="mb-4">
            <label for="name" class="block text-xs font-medium text-gray-700 mb-2">
                Nama Lengkap
            </label>

            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name', $user->name) }}"
                class="w-full border border-gray-300 rounded-md px-2 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                required
            >

            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- EMAIL --}}
        <div class="mb-4">
            <label for="email" class="block text-xs font-medium text-gray-700 mb-2">
                Email
            </label>

            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email', $user->email) }}"
                class="w-full border border-gray-300 rounded-md px-2 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                required
            >

            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- PASSWORD BARU --}}
        <div class="mb-4">
            <label for="password" class="block text-xs font-medium text-gray-700 mb-2">
                Password Baru
                <span class="text-gray-400 font-normal">
                    (Kosongkan jika tidak ingin mengubah password)
                </span>
            </label>

            <input
                type="password"
                name="password"
                id="password"
                class="w-full border border-gray-300 rounded-md px-2 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
            >

            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- FOTO PROFIL --}}
        <div class="mb-4">
            <label for="foto_profile" class="block text-xs font-medium text-gray-700 mb-2">
                Foto Profil
            </label>

            @if($user->foto_profile)
                <div class="mb-2">
                    <img
                        src="{{ asset($user->foto_profile) }}"
                        alt="Foto Profil"
                        class="w-16 h-16 object-cover rounded-full border border-gray-300"
                    >
                </div>
            @else
                <div class="w-16 h-16 mb-2 rounded-full border border-gray-300 flex items-center justify-center text-[9px] text-gray-400">
                    Belum ada foto
                </div>
            @endif

            <input
                type="file"
                name="foto_profile"
                id="foto_profile"
                accept="image/jpeg,image/png,image/jpg"
                class="w-full border border-gray-300 rounded-md px-2 py-2 text-sm"
            >

            <p class="text-gray-500 text-[9px] mt-1">
                Pilih foto baru jika ingin mengganti foto profil. Format JPG, JPEG, PNG. Maksimal 2 MB.
            </p>

            @error('foto_profile')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ROLE / HAK AKSES --}}
        <div class="mb-4">
            <label for="role" class="block text-xs font-medium text-gray-700 mb-2">
                Role / Hak Akses
            </label>

            <select
                name="role"
                id="role"
                class="w-full border border-gray-300 rounded-md px-2 py-2 text-sm bg-gray-100 focus:outline-none focus:ring-1 focus:ring-blue-500"
                required
            >
                <option value="admin"
                    {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                    Admin
                </option>

                <option value="petugas"
                    {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>
                    Petugas
                </option>

                <option value="peminjam"
                    {{ old('role', $user->role) == 'peminjam' ? 'selected' : '' }}>
                    Peminjam
                </option>
            </select>

            @error('role')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- NO. HP --}}
        <div class="mb-4">
            <label for="no_hp" class="block text-xs font-medium text-gray-700 mb-2">
                No. HP
            </label>

            <input
                type="text"
                name="no_hp"
                id="no_hp"
                value="{{ old('no_hp', $user->no_hp) }}"
                class="w-full border border-gray-300 rounded-md px-2 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
            >

            @error('no_hp')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- TOMBOL --}}
        <div class="flex justify-end gap-2 mt-5">

            <a
                href="{{ route('admin.user.index') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm"
            >
                Batal
            </a>

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm"
            >
                Perbarui
            </button>

        </div>

    </form>

</div>

@endsection