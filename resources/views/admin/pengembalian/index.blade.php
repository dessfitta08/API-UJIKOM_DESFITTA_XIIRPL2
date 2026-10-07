@extends('layouts.app')

@section('title', 'Kelola Pengembalian')
@section('header-title', 'Kelola Pengembalian')

@section('content')

<div class="container mx-auto">

    {{-- PESAN BERHASIL --}}
    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg shadow-sm text-sm">
            {{ session('success') }}
        </div>
    @endif


    {{-- PESAN ERROR --}}
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg shadow-sm text-sm">
            {{ session('error') }}
        </div>
    @endif


    {{-- ========================================================= --}}
    {{-- PEMINJAMAN AKTIF --}}
    {{-- ========================================================= --}}

    <div class="mb-8">

        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800">
                Peminjaman Aktif
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Peminjaman yang sedang dipinjam atau sudah melewati batas pengembalian.
            </p>
        </div>


        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-left border-collapse">

                    <thead>

                        <tr class="bg-gray-800 text-white text-sm">

                            <th class="px-4 py-3">
                                No
                            </th>

                            <th class="px-4 py-3">
                                Peminjam
                            </th>

                            <th class="px-4 py-3">
                                Alat
                            </th>

                            <th class="px-4 py-3">
                                Tanggal Pinjam
                            </th>

                            <th class="px-4 py-3">
                                Jatuh Tempo
                            </th>

                            <th class="px-4 py-3">
                                Status
                            </th>

                            <th class="px-4 py-3 text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="text-gray-700 text-sm">

                        @forelse($peminjamanAktif as $peminjaman)

                            <tr class="border-b hover:bg-gray-50">

                                {{-- NO --}}
                                <td class="px-4 py-3">
                                    {{ $loop->iteration }}
                                </td>


                                {{-- PEMINJAM --}}
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $peminjaman->user->name ?? '-' }}
                                </td>


                                {{-- ALAT --}}
                                <td class="px-4 py-3">

                                    @forelse($peminjaman->detailPinjams as $detail)

                                        <div class="mb-1 last:mb-0">

                                            {{ $detail->alat->nama_alat ?? '-' }}

                                            <span class="text-gray-500">
                                                ({{ $detail->jumlah }} unit)
                                            </span>

                                        </div>

                                    @empty

                                        <span class="text-gray-400">
                                            -
                                        </span>

                                    @endforelse

                                </td>


                                {{-- TANGGAL PINJAM --}}
                                <td class="px-4 py-3 whitespace-nowrap">

                                    {{ $peminjaman->tgl_pinjam
                                        ? \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('Y-m-d')
                                        : '-' }}

                                </td>


                                {{-- JATUH TEMPO --}}
                                <td class="px-4 py-3 whitespace-nowrap">

                                    {{ $peminjaman->tgl_kembali_plan
                                        ? \Carbon\Carbon::parse($peminjaman->tgl_kembali_plan)->format('Y-m-d')
                                        : '-' }}

                                </td>


                                {{-- STATUS --}}
                                <td class="px-4 py-3">

                                    @if(strtolower($peminjaman->status) === 'telat')

                                        <span class="inline-flex px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                            Telat
                                        </span>

                                    @else

                                        <span class="inline-flex px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                            Dipinjam
                                        </span>

                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td class="px-4 py-3 text-center">

                                    <a
                                        href="{{ route('admin.pengembalian.create', $peminjaman->id) }}"
                                        class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-xs font-semibold transition"
                                    >
                                        Proses Pengembalian
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="px-4 py-8 text-center text-gray-500"
                                >
                                    Tidak ada peminjaman aktif.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- DATA PENGEMBALIAN --}}
    {{-- ========================================================= --}}

    <div>

        <div class="mb-4">

            <h2 class="text-2xl font-bold text-gray-800">
                Data Pengembalian
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Daftar peminjaman yang sudah dikembalikan.
            </p>

        </div>


        {{-- SEARCH --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-5">

            <form
                method="GET"
                action="{{ route('admin.pengembalian.index') }}"
                class="flex gap-2"
            >

                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari nama peminjam, alat, kondisi, atau denda..."
                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                >


                <button
                    type="submit"
                    class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-semibold transition"
                >
                    Cari
                </button>


                @if($search ?? false)

                    <a
                        href="{{ route('admin.pengembalian.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-5 py-2 rounded-lg text-sm font-semibold transition"
                    >
                        Reset
                    </a>

                @endif

            </form>

        </div>



        {{-- TABLE DATA PENGEMBALIAN --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-left border-collapse">

                    <thead>

                        <tr class="bg-gray-800 text-white text-sm">

                            <th class="px-4 py-3">
                                No
                            </th>

                            <th class="px-4 py-3">
                                Peminjam
                            </th>

                            <th class="px-4 py-3">
                                Alat
                            </th>

                            <th class="px-4 py-3">
                                Tanggal Kembali
                            </th>

                            <th class="px-4 py-3">
                                Kondisi
                            </th>

                            <th class="px-4 py-3">
                                Total Denda
                            </th>

                            <th class="px-4 py-3">
                                Petugas
                            </th>

                            <th class="px-4 py-3 text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="text-gray-700 text-sm">

                        @forelse($pengembalians as $pengembalian)

                            <tr class="border-b hover:bg-gray-50">

                                {{-- NO --}}
                                <td class="px-4 py-3">
                                    {{ $pengembalians->firstItem() + $loop->index }}
                                </td>


                                {{-- PEMINJAM --}}
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">

                                    {{ $pengembalian->peminjaman->user->name ?? '-' }}

                                </td>


                                {{-- ALAT --}}
                                <td class="px-4 py-3">

                                    @forelse($pengembalian->peminjaman->detailPinjams as $detail)

                                        <div class="mb-1 last:mb-0">

                                            {{ $detail->alat->nama_alat ?? '-' }}

                                            <span class="text-gray-500">
                                                ({{ $detail->jumlah }} unit)
                                            </span>

                                        </div>

                                    @empty

                                        <span class="text-gray-400">
                                            -
                                        </span>

                                    @endforelse

                                </td>


                                {{-- TANGGAL KEMBALI --}}
                                <td class="px-4 py-3 whitespace-nowrap">

                                    {{ $pengembalian->tgl_kembali
                                        ? \Carbon\Carbon::parse($pengembalian->tgl_kembali)->format('Y-m-d')
                                        : '-' }}

                                </td>


                                {{-- KONDISI --}}
                                <td class="px-4 py-3 whitespace-nowrap">

                                    @if($pengembalian->kondisi_kembali)

                                        <span class="text-gray-700 font-medium">
                                            {{ $pengembalian->kondisi_kembali }}
                                        </span>

                                    @else

                                        <span class="text-gray-400">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- DENDA --}}
                                <td class="px-4 py-3 font-medium whitespace-nowrap">

                                    Rp {{ number_format(
                                        $pengembalian->denda ?? 0,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>


                                {{-- PETUGAS --}}
                                <td class="px-4 py-3 whitespace-nowrap">

                                    {{ $pengembalian->petugas->name ?? '-' }}

                                </td>


                                {{-- AKSI --}}
                                <td class="px-4 py-3">

                                    <div class="flex justify-center items-center gap-2">

                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route('admin.pengembalian.edit', $pengembalian->id) }}"
                                            class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded text-xs font-semibold transition"
                                        >
                                            Edit
                                        </a>


                                        {{-- HAPUS --}}
                                        <form
                                            action="{{ route('admin.pengembalian.destroy', $pengembalian->id) }}"
                                            method="POST"
                                            class="delete-form"
                                            data-delete-type="pengembalian"
                                            data-delete-message="Apakah yakin ingin menghapus data pengembalian ini?"
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

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="px-4 py-10 text-center text-gray-500"
                                >
                                    Belum ada data pengembalian.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- PAGINATION --}}
        <div class="mt-5">

            {{ $pengembalians->links() }}

        </div>

    </div>

</div>

@endsection