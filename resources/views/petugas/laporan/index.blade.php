@extends('layouts.app')

@section('title', 'Cetak Laporan')

@section('header-title', 'Cetak Laporan')

@section('content')

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

    {{-- =========================================================
         JUDUL LAPORAN
    ========================================================== --}}

    <div class="px-5 py-5 border-b border-gray-200">

        <h2 class="text-xl font-bold text-gray-800">
            Laporan Peminjaman
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Data laporan peminjaman alat
        </p>

    </div>


    {{-- =========================================================
         FILTER LAPORAN
    ========================================================== --}}

    <div class="px-5 py-5 border-b border-gray-200">

        <form
            action="{{ route('petugas.laporan.index') }}"
            method="GET"
            class="flex items-end gap-4"
        >

            {{-- STATUS PEMINJAMAN --}}

            <div class="flex-1 min-w-0">

                <label
                    for="status"
                    class="block text-xs font-medium text-gray-700 mb-1"
                >
                    Status Peminjaman:
                </label>

                <select
                    name="status"
                    id="status"
                    class="w-full h-9 px-3 border border-gray-300 rounded-lg bg-white text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-slate-400"
                >

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="selesai"
                        {{ request('status') == 'selesai' ? 'selected' : '' }}
                    >
                        Selesai
                    </option>

                </select>

            </div>


            {{-- DARI TANGGAL --}}

            <div class="flex-1 min-w-0">

                <label
                    for="tanggal_mulai"
                    class="block text-xs font-medium text-gray-700 mb-1"
                >
                    Dari Tanggal (Pinjam):
                </label>

                <input
                    type="date"
                    name="tanggal_mulai"
                    id="tanggal_mulai"
                    value="{{ request('tanggal_mulai') }}"
                    class="w-full h-9 px-3 border border-gray-300 rounded-lg bg-white text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-slate-400"
                >

            </div>


            {{-- SAMPAI TANGGAL --}}

            <div class="flex-1 min-w-0">

                <label
                    for="tanggal_selesai"
                    class="block text-xs font-medium text-gray-700 mb-1"
                >
                    Sampai Tanggal (Pinjam):
                </label>

                <input
                    type="date"
                    name="tanggal_selesai"
                    id="tanggal_selesai"
                    value="{{ request('tanggal_selesai') }}"
                    class="w-full h-9 px-3 border border-gray-300 rounded-lg bg-white text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-slate-400"
                >

            </div>


            {{-- TOMBOL FILTER --}}

            <div class="flex-shrink-0">

                <button
                    type="submit"
                    class="h-9 px-8 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-lg transition"
                >
                    Filter
                </button>

            </div>


            {{-- TOMBOL RESET --}}

            <div class="flex-shrink-0">

                <a
                    href="{{ route('petugas.laporan.index') }}"
                    class="h-9 px-4 flex items-center justify-center bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-lg transition"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- =========================================================
         TOMBOL CETAK
    ========================================================== --}}

    <div class="px-5 py-4 flex justify-end border-b border-gray-200">

        <a
            href="{{ route('petugas.laporan.cetak', request()->query()) }}"
            target="_blank"
            class="inline-flex items-center px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition"
        >
            Cetak Laporan
        </a>

    </div>


    {{-- =========================================================
         TABEL LAPORAN
    ========================================================== --}}

    <div class="overflow-x-auto">

        <table class="w-full text-sm border-collapse">

            <thead>

                <tr class="bg-slate-800 text-white">

                    <th class="py-3 px-4 text-center font-semibold whitespace-nowrap">
                        No
                    </th>

                    <th class="py-3 px-4 text-center font-semibold whitespace-nowrap">
                        Peminjam
                    </th>

                    <th class="py-3 px-4 text-center font-semibold whitespace-nowrap">
                        Tgl Pinjam
                    </th>

                    <th class="py-3 px-4 text-center font-semibold whitespace-nowrap">
                        Rencana Kembali
                    </th>

                    <th class="py-3 px-4 text-center font-semibold whitespace-nowrap">
                        Status
                    </th>

                    <th class="py-3 px-4 text-center font-semibold whitespace-nowrap">
                        Detail Alat
                    </th>

                    <th class="py-3 px-4 text-center font-semibold whitespace-nowrap">
                        Denda
                    </th>

                </tr>

            </thead>


            <tbody class="text-gray-700">

                @forelse($laporans as $index => $item)

                    <tr class="hover:bg-gray-50 transition">

                        {{-- NO --}}

                        <td class="py-3 px-4 border-b text-center">
                            {{ $index + 1 }}
                        </td>


                        {{-- PEMINJAM --}}

                        <td class="py-3 px-4 border-b text-center">
                            {{ $item->user->name ?? '-' }}
                        </td>


                        {{-- TANGGAL PINJAM --}}

                        <td class="py-3 px-4 border-b text-center whitespace-nowrap">
                            {{ $item->tgl_pinjam ?? '-' }}
                        </td>


                        {{-- RENCANA KEMBALI --}}

                        <td class="py-3 px-4 border-b text-center whitespace-nowrap">
                            {{ $item->tgl_kembali_plan ?? '-' }}
                        </td>


                        {{-- STATUS --}}

                        <td class="py-3 px-4 border-b text-center">

                            @if(strtolower($item->status) === 'selesai')

                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    Selesai
                                </span>

                            @elseif(strtolower($item->status) === 'dipinjam')

                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                    Dipinjam
                                </span>

                            @elseif(strtolower($item->status) === 'telat')

                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    Telat
                                </span>

                            @elseif(strtolower($item->status) === 'ditolak')

                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    Ditolak
                                </span>

                            @else

                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                    {{ ucfirst($item->status ?? '-') }}
                                </span>

                            @endif

                        </td>


                        {{-- DETAIL ALAT --}}

                        <td class="py-3 px-4 border-b text-left">

                            @forelse($item->detailPinjams as $detail)

                                <div class="mb-1">

                                    {{ $detail->alat->nama_alat ?? '-' }}

                                    <span class="text-gray-500">
                                        ({{ $detail->jumlah }})
                                    </span>

                                </div>

                            @empty

                                <span class="text-gray-400">
                                    Tidak ada alat
                                </span>

                            @endforelse

                        </td>


                        {{-- DENDA --}}

                        <td class="py-3 px-4 border-b text-center whitespace-nowrap">

                            Rp
                            {{ number_format(
                                $item->pengembalian->denda ?? 0,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="py-10 px-4 text-center text-gray-500"
                        >
                            Tidak ada data laporan yang sesuai.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection