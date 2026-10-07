@extends('layouts.app')

@section('title', 'Kelola Peminjaman - Panel Admin')
@section('header-title', 'Manajemen Transaksi Peminjaman')

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

        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="p-5 border-b border-gray-200 bg-gray-50">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                {{-- Judul --}}
                <h3 class="text-lg font-bold text-gray-800">
                    Daftar Transaksi Peminjaman
                </h3>


                {{-- Search + Tambah --}}
                <div class="flex items-center gap-3 w-full md:w-auto">

                    {{-- Form Search --}}
                    <form
                        action="{{ route('admin.peminjaman.index') }}"
                        method="GET"
                        class="flex w-full md:w-80"
                    >

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama peminjam / alat / status..."
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
                                href="{{ route('admin.peminjaman.index') }}"
                                class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-2 text-sm rounded-lg flex items-center transition"
                            >
                                Reset
                            </a>
                        @endif

                    </form>


                    {{-- Tambah Peminjaman --}}
                    <a
                        href="{{ route('admin.peminjaman.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition whitespace-nowrap"
                    >
                        + Tambah Peminjaman
                    </a>

                </div>

            </div>

        </div>


        {{-- =========================================================
             TABEL
        ========================================================== --}}
        <div class="overflow-x-auto">

            <table class="w-full border-collapse">

                {{-- HEADER TABEL --}}
                <thead>

                    <tr class="bg-slate-800 text-white text-sm uppercase tracking-wider">

                        <th class="py-3 px-4 text-left whitespace-nowrap">
                            Peminjam
                        </th>

                        <th class="py-3 px-4 text-left">
                            Alat yang Dipinjam
                        </th>

                        <th class="py-3 px-4 text-left">
                            Tgl Pinjam / Rencana Kembali
                        </th>

                        <th class="py-3 px-4 text-center whitespace-nowrap">
                            Status
                        </th>

                        <th class="py-3 px-4 text-center whitespace-nowrap">
                            Aksi
                        </th>

                    </tr>

                </thead>


                {{-- ISI TABEL --}}
                <tbody class="text-gray-700 text-sm">

                    @forelse($peminjamans as $peminjaman)

                        <tr class="hover:bg-gray-50 transition">


                            {{-- =================================================
                                 PEMINJAM
                            ================================================== --}}
                            <td class="py-3 px-4 border-b align-middle">

                                <span class="font-medium text-gray-900">
                                    {{ $peminjaman->user->name ?? 'User Dihapus' }}
                                </span>

                            </td>


                            {{-- =================================================
                                 ALAT YANG DIPINJAM
                            ================================================== --}}
                            <td class="py-3 px-4 border-b align-middle">

                                @forelse($peminjaman->detailPinjams as $detail)

                                    <div class="flex items-center gap-2 mb-1 last:mb-0">

                                        <span class="text-gray-500">
                                            •
                                        </span>

                                        <span class="text-gray-800">
                                            {{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}
                                        </span>

                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-600 text-xs whitespace-nowrap">
                                            {{ $detail->jumlah }} pcs
                                        </span>

                                    </div>

                                @empty

                                    <span class="text-gray-400 italic">
                                        Tidak ada alat
                                    </span>

                                @endforelse

                            </td>


                            {{-- =================================================
                                 TANGGAL
                            ================================================== --}}
                            <td class="py-3 px-4 border-b align-middle">

                                <div class="space-y-1 text-sm">

                                    <div>
                                        <span class="text-gray-500">
                                            Pinjam:
                                        </span>

                                        <span class="text-gray-800">
                                            {{ $peminjaman->tgl_pinjam }}
                                        </span>
                                    </div>


                                    <div>
                                        <span class="text-gray-500">
                                            Rencana:
                                        </span>

                                        <span class="text-gray-800">
                                            {{ $peminjaman->tgl_kembali_plan }}
                                        </span>
                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                 STATUS
                            ================================================== --}}
                            <td class="py-3 px-4 border-b text-center align-middle">

                                @if($peminjaman->status == 'diajukan')

                                    <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Diajukan
                                    </span>

                                @elseif($peminjaman->status == 'dipinjam')

                                    <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Dipinjam
                                    </span>

                                @elseif($peminjaman->status == 'selesai')

                                    <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                        Selesai
                                    </span>

                                @elseif($peminjaman->status == 'telat')

                                    <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Telat
                                    </span>

                                @elseif($peminjaman->status == 'ditolak')

                                    <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Ditolak
                                    </span>

                                @else

                                    <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 AKSI
                            ================================================== --}}
                            <td class="py-3 px-4 border-b text-center align-middle">

                                {{-- Semua aksi disusun VERTIKAL --}}
                                <div class="flex flex-col items-center justify-center gap-2">


                                    {{-- UPDATE STATUS --}}
                                    <form
                                        action="{{ route('admin.peminjaman.updateStatus', $peminjaman->id) }}"
                                        method="POST"
                                        class="w-full max-w-[120px]"
                                    >

                                        @csrf
                                        @method('PUT')

                                        <select
                                            name="status"
                                            onchange="this.form.submit()"
                                            class="w-full px-2 py-2 text-xs border border-gray-300 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer"
                                        >

                                            <option
                                                value="diajukan"
                                                {{ $peminjaman->status == 'diajukan' ? 'selected' : '' }}
                                            >
                                                Diajukan
                                            </option>

                                            <option
                                                value="dipinjam"
                                                {{ $peminjaman->status == 'dipinjam' ? 'selected' : '' }}
                                            >
                                                Dipinjam
                                            </option>

                                            <option
                                                value="selesai"
                                                {{ $peminjaman->status == 'selesai' ? 'selected' : '' }}
                                            >
                                                Selesai
                                            </option>

                                            <option
                                                value="telat"
                                                {{ $peminjaman->status == 'telat' ? 'selected' : '' }}
                                            >
                                                Telat
                                            </option>

                                        </select>

                                    </form>


                                    {{-- HAPUS --}}
                                    @if(strtolower($peminjaman->status) === 'selesai')

                                        <form
                                            action="{{ route('admin.peminjaman.destroy', $peminjaman->id) }}"
                                            method="POST"
                                            class="delete-form w-full max-w-[120px]"
                                            data-delete-type="peminjaman"
                                            data-delete-message="Peminjaman ini sudah selesai. Apakah yakin ingin menghapus data peminjaman ini?"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                class="delete-button w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-xs font-semibold transition"
                                            >
                                                Hapus
                                            </button>

                                        </form>

                                    @else

                                        {{-- Jika belum selesai --}}
                                        <span class="text-xs text-gray-400 italic">
                                            Belum dikembalikan
                                        </span>

                                    @endif

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="py-10 text-center text-gray-500"
                            >
                                Belum ada data peminjaman.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =========================================================
             PAGINATION
        ========================================================== --}}
        <div class="p-4 border-t border-gray-200 bg-gray-50">

            {{ $peminjamans->links() }}

        </div>

    </div>

@endsection