@extends('layouts.app')

@section('title', 'Edit Alat - Panel Admin')
@section('header-title', 'Edit Data Alat')

@section('content')

@if ($errors->any())
    <div class="max-w-2xl mb-4 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg">
        <p class="font-semibold mb-2">
            Data belum berhasil diperbarui:
        </p>

        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="max-w-2xl mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="max-w-2xl mb-4 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg">
        {{ session('error') }}
    </div>
@endif


<div class="max-w-2xl bg-white rounded-lg shadow-sm border border-gray-200 p-6">

    <form
        action="{{ route('admin.alat.update', $alat->id) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')


        {{-- Nama Alat --}}
        <div class="mb-4">

            <label class="block text-gray-700 text-sm font-semibold mb-2">
                Nama Alat
            </label>

            <input
                type="text"
                name="nama_alat"
                value="{{ old('nama_alat', $alat->nama_alat) }}"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg
                       focus:outline-none focus:ring-2 focus:ring-blue-500"
            >

            @error('nama_alat')
                <span class="text-red-500 text-xs">
                    {{ $message }}
                </span>
            @enderror

        </div>


        {{-- Kategori --}}
        <div class="mb-4">

            <label class="block text-gray-700 text-sm font-semibold mb-2">
                Kategori
            </label>

            <select
                name="kategori_id"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg
                       focus:outline-none focus:ring-2 focus:ring-blue-500"
            >

                <option value="">
                    -- Pilih Kategori --
                </option>

                @foreach($kategoris as $kategori)

                    <option
                        value="{{ $kategori->id }}"
                        {{ old('kategori_id', $alat->kategori_id) == $kategori->id ? 'selected' : '' }}
                    >
                        {{ $kategori->nama_kategori }}
                    </option>

                @endforeach

            </select>

            @error('kategori_id')
                <span class="text-red-500 text-xs">
                    {{ $message }}
                </span>
            @enderror

        </div>


        {{-- Stok dan Kondisi --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

            {{-- Stok --}}
            <div>

                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Stok
                </label>

                <input
                    type="number"
                    name="stok"
                    value="{{ old('stok', $alat->stok) }}"
                    min="0"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

                @error('stok')
                    <span class="text-red-500 text-xs">
                        {{ $message }}
                    </span>
                @enderror

            </div>


            {{-- Status Kondisi --}}
            <div>

                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Status Kondisi
                </label>

                <input
                    type="text"
                    name="status_kondisi"
                    value="{{ old('status_kondisi', $alat->status_kondisi) }}"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

                @error('status_kondisi')
                    <span class="text-red-500 text-xs">
                        {{ $message }}
                    </span>
                @enderror

            </div>

        </div>


        {{-- Deskripsi --}}
        <div class="mb-4">

            <label class="block text-gray-700 text-sm font-semibold mb-2">
                Deskripsi
            </label>

            <textarea
                name="deskripsi"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg
                       focus:outline-none focus:ring-2 focus:ring-blue-500"
            >{{ old('deskripsi', $alat->deskripsi) }}</textarea>

            @error('deskripsi')
                <span class="text-red-500 text-xs">
                    {{ $message }}
                </span>
            @enderror

        </div>


        {{-- Gambar --}}
        <div class="mb-6">

            <label class="block text-gray-700 text-sm font-semibold mb-2">
                Gambar Alat
                <span class="text-xs text-gray-400 font-normal">
                    (Biarkan kosong jika tidak ingin mengubah gambar)
                </span>
            </label>


            {{-- Gambar Lama --}}
            @if($alat->gambar)

                <div class="mb-3">

                    <p class="text-xs text-gray-500 mb-2">
                        Gambar saat ini:
                    </p>

                    <img
                        src="{{ asset('storage/' . $alat->gambar) }}"
                        alt="{{ $alat->nama_alat }}"
                        class="w-24 h-24 object-cover rounded-lg border border-gray-300"
                    >

                </div>

            @endif


            {{-- Upload Gambar Baru --}}
            <input
                type="file"
                name="gambar"
                accept=".jpg,.jpeg,.png"
                class="w-full text-sm text-gray-500
                       file:mr-4 file:py-2 file:px-4
                       file:rounded-lg file:border-0
                       file:text-sm file:font-semibold
                       file:bg-blue-50 file:text-blue-700
                       hover:file:bg-blue-100"
            >

            @error('gambar')
                <span class="text-red-500 text-xs">
                    {{ $message }}
                </span>
            @enderror

            <p class="text-xs text-gray-500 mt-1">
                Format yang diperbolehkan: JPG, JPEG, PNG. Maksimal 2 MB.
            </p>

        </div>


        {{-- Tombol --}}
        <div class="flex justify-end space-x-2">

            <a
                href="{{ route('admin.alat.index') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800
                       px-4 py-2 rounded-lg text-sm font-semibold transition"
            >
                Batal
            </a>

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white
                       px-4 py-2 rounded-lg text-sm font-semibold transition"
            >
                Perbarui
            </button>

        </div>

    </form>

</div>

@endsection