@extends('layouts.app')

@section('title', 'Kelola User - Panel Admin')
@section('header-title', 'Manajemen Pengguna Sistem')

@section('content')

    {{-- Notifikasi sukses --}}
    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg shadow-sm text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Notifikasi error --}}
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg shadow-sm text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">

        {{-- Header --}}
        <div class="p-5 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row md:justify-between md:items-center gap-4">

            <h3 class="text-lg font-bold text-gray-800">
                Daftar Pengguna Sistem
            </h3>

            <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">

                {{-- Form Search --}}
                <form
                    action="{{ route('admin.user.index') }}"
                    method="GET"
                    class="flex w-full md:w-80"
                >

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama, email, role..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >

                    <button
                        type="submit"
                        class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 text-sm font-semibold rounded-r-lg transition"
                    >
                        Cari
                    </button>

                    @if(request('search'))
                        <a
                            href="{{ route('admin.user.index') }}"
                            class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-2 text-sm rounded-lg flex items-center transition"
                            title="Reset Pencarian"
                        >
                            Reset
                        </a>
                    @endif

                </form>

                {{-- Tombol Tambah User --}}
                <a
                    href="{{ route('admin.user.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition whitespace-nowrap"
                >
                    + Tambah User
                </a>

            </div>

        </div>


        {{-- Tabel --}}
        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse">

                {{-- Header Tabel --}}
                <thead>
                    <tr class="bg-slate-800 text-white text-sm uppercase tracking-wider">

                        {{-- Foto Tengah --}}
                        <th class="py-3 px-4 text-center">
                            Foto
                        </th>

                        {{-- Nama Kiri --}}
                        <th class="py-3 px-4 text-left">
                            Nama
                        </th>

                        {{-- Email Kiri --}}
                        <th class="py-3 px-4 text-left">
                            Email
                        </th>

                        {{-- Role Tengah --}}
                        <th class="py-3 px-4 text-center">
                            Role / Hak Akses
                        </th>

                        {{-- No HP Tengah --}}
                        <th class="py-3 px-4 text-center">
                            No. HP
                        </th>

                        {{-- Aksi Tengah --}}
                        <th class="py-3 px-4 text-center">
                            Aksi
                        </th>

                    </tr>
                </thead>


                {{-- Isi Tabel --}}
                <tbody class="text-gray-700 text-sm">

                    @forelse($users as $user)

                        <tr class="hover:bg-gray-50 transition">

                            {{-- FOTO --}}
                            <td class="py-3 px-4 border-b text-center">

                                @if($user->foto_profile)

                                    <img
                                        src="{{ asset($user->foto_profile) }}"
                                        alt="Foto {{ $user->name }}"
                                        class="w-10 h-10 rounded-full object-cover border border-gray-300 mx-auto"
                                    >

                                @else

                                    <div
                                        class="w-10 h-10 rounded-full bg-gray-700 text-white flex items-center justify-center font-bold mx-auto"
                                    >
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                @endif

                            </td>


                            {{-- NAMA --}}
                            <td class="py-3 px-4 border-b font-medium text-gray-900 text-left">
                                {{ $user->name }}
                            </td>


                            {{-- EMAIL --}}
                            <td class="py-3 px-4 border-b text-left">
                                {{ $user->email }}
                            </td>


                            {{-- ROLE --}}
                            <td class="py-3 px-4 border-b text-center">

                                <span
                                    class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full
                                    @if($user->role == 'admin')
                                        bg-purple-100 text-purple-800
                                    @elseif($user->role == 'petugas')
                                        bg-blue-100 text-blue-800
                                    @else
                                        bg-green-100 text-green-800
                                    @endif"
                                >
                                    {{ ucfirst($user->role) }}
                                </span>

                            </td>


                            {{-- NO HP --}}
                            <td class="py-3 px-4 border-b text-center">
                                {{ $user->no_hp ?? '-' }}
                            </td>

                            {{-- AKSI --}}
                            <td class="py-3 px-4 border-b text-center">

                                <div class="flex items-center justify-center gap-2">

                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('admin.user.edit', $user->id) }}"
                                        class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded text-xs font-semibold transition"
                                    >
                                        Edit
                                    </a>

                                    {{-- Hapus hanya untuk Petugas dan Peminjam --}}
                                    @if($user->role !== 'admin')

                                        <form
                                            action="{{ route('admin.user.destroy', $user->id) }}"
                                            method="POST"
                                            class="delete-form inline"
                                            data-delete-type="user"
                                            data-delete-message="Apakah yakin ingin menghapus user ini?"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                class="delete-button bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded text-xs font-semibold transition"
                                            >
                                                Hapus
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>
                            
                                    
                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="6"
                                class="py-8 text-center text-gray-500"
                            >
                                Belum ada data pengguna.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        <div class="p-4 border-t border-gray-200 bg-gray-50">
            {{ $users->links() }}
        </div>

    </div>

@endsection