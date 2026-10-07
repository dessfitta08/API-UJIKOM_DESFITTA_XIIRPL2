@extends('layouts.app')

@section('title', 'Pemantauan Pengembalian - Dashboard Petugas')
@section('header-title', 'Pemantauan & Proses Pengembalian Alat')

@section('content')

@if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg shadow-sm text-sm">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg shadow-sm text-sm">
        {{ session('error') }}
    </div>
@endif


<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">

    <!-- HEADER -->
    <div class="p-5 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">

        <h3 class="text-lg font-bold text-gray-800">
            Daftar Peminjaman Aktif (Belum Kembali)
        </h3>

        <form
            action="{{ route('petugas.pengembalian.index') }}"
            method="GET"
            class="flex w-full md:w-80"
        >

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama peminjam..."
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
            >

            <button
                type="submit"
                class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 text-sm font-semibold rounded-r-lg transition"
            >
                Cari
            </button>

            @if(request('search'))
                <a
                    href="{{ route('petugas.pengembalian.index') }}"
                    class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-2 text-sm rounded-lg flex items-center transition"
                >
                    Reset
                </a>
            @endif

        </form>

    </div>


    <!-- TABLE -->
    <div class="overflow-x-auto">

        <table class="w-full text-left border-collapse">

            <!-- HEADER TABEL -->
            <thead>

                <tr class="bg-slate-800 text-white">

                    <th class="py-3 px-4 text-left font-semibold whitespace-nowrap">
                        Peminjam
                    </th>

                    <th class="py-3 px-4 text-left font-semibold whitespace-nowrap">
                        Tgl Pinjam
                    </th>

                    <th class="py-3 px-4 text-left font-semibold whitespace-nowrap">
                        Rencana Kembali
                    </th>

                    <th class="py-3 px-4 text-left font-semibold whitespace-nowrap">
                        Status
                    </th>

                    <th class="py-3 px-4 text-left font-semibold">
                        Detail Alat
                    </th>

                    <th class="py-3 px-4 text-center font-semibold whitespace-nowrap">
                        Aksi Pengembalian
                    </th>

                </tr>

            </thead>


            <!-- ISI TABEL -->
            <tbody class="text-gray-700 text-sm">

                @forelse($peminjamans as $item)

                <tr class="hover:bg-gray-50 transition align-top">

                    <!-- PEMINJAM -->
                    <td class="py-3 px-4 border-b font-medium text-gray-900">
                        {{ $item->user->name ?? 'User Dihapus' }}
                    </td>


                    <!-- TANGGAL PINJAM -->
                    <td class="py-3 px-4 border-b">
                        {{ $item->tgl_pinjam }}
                    </td>


                    <!-- RENCANA KEMBALI -->
                    <td class="py-3 px-4 border-b">
                        {{ $item->tgl_kembali_plan }}
                    </td>


                    <!-- STATUS -->
                    <td class="py-3 px-4 border-b">

                        <span
                            class="px-2.5 py-1 rounded text-xs font-semibold
                            {{ strtolower($item->status) == 'telat'
                                ? 'bg-red-100 text-red-700'
                                : 'bg-blue-100 text-blue-700' }}"
                        >

                            {{ ucfirst($item->status) }}

                        </span>

                    </td>


                    <!-- DETAIL ALAT -->
                    <td class="py-3 px-4 border-b">

                        <ul class="list-disc list-inside space-y-1 text-xs">

                            @foreach($item->detailPinjams as $detail)

                            <li>

                                <span class="font-semibold">
                                    {{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}
                                </span>

                                (Jumlah: {{ $detail->jumlah }})

                            </li>

                            @endforeach

                        </ul>

                    </td>


                    <!-- AKSI PENGEMBALIAN -->
                    <td class="py-3 px-4 border-b text-center">

                        <form
                            action="{{ route('petugas.pengembalian.proses', $item->id) }}"
                            method="POST"
                            class="return-form inline-block bg-gray-50 p-3 rounded border border-gray-200 text-left space-y-2"
                        >

                            @csrf


                            <!-- KONDISI KEMBALI -->
                            <div>

                                <label class="block text-xs font-semibold text-gray-600 mb-1">
                                    Kondisi Kembali:
                                </label>

                                <select
                                    name="kondisi_kembali"
                                    required
                                    class="w-full text-xs border border-gray-300 rounded px-2 py-1 focus:ring-emerald-500 focus:border-emerald-500"
                                >

                                    <option value="Baik">
                                        Baik
                                    </option>

                                    <option value="Rusak Ringan">
                                        Rusak Ringan
                                    </option>

                                    <option value="Rusak Berat">
                                        Rusak Berat
                                    </option>

                                </select>

                            </div>


                            <!-- DENDA -->
                            <div>

                                <label class="block text-xs font-semibold text-gray-600 mb-1">
                                    Denda (Rp):
                                </label>

                                <input
                                    type="number"
                                    name="denda"
                                    value="0"
                                    min="0"
                                    placeholder="0"
                                    class="w-full text-xs border border-gray-300 rounded px-2 py-1 focus:ring-emerald-500 focus:border-emerald-500"
                                >

                            </div>


                            <!-- TOMBOL -->
                            <button
                                type="button"
                                class="return-button w-full bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded text-xs font-semibold transition shadow-sm"
                            >

                                Terima Pengembalian

                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td
                        colspan="6"
                        class="py-6 text-center text-gray-500"
                    >

                        Tidak ada peminjaman yang sedang aktif saat ini.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


<!-- ===================================================== -->
<!-- MODAL KONFIRMASI PENGEMBALIAN -->
<!-- ===================================================== -->

<div
    id="returnModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4"
>

    <div
        class="w-full max-w-md bg-white rounded-lg shadow-xl border border-gray-200"
        onclick="event.stopPropagation()"
    >


        <!-- JUDUL -->
        <div class="px-6 py-4 border-b border-gray-200">

            <h2 class="text-lg font-semibold text-gray-800">
                Konfirmasi Pengembalian
            </h2>

        </div>


        <!-- PESAN -->
        <div class="px-6 py-5">

            <p class="text-sm text-gray-600 leading-relaxed">

                Apakah yakin ingin memproses pengembalian alat ini?

            </p>

        </div>


        <!-- TOMBOL -->
        <div class="px-6 pb-5">

            <div class="flex items-center justify-between gap-3">


                <!-- BATAL -->
                <button
                    type="button"
                    id="returnCancelButton"
                    class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition"
                >

                    Batal

                </button>


                <!-- YAKIN TERIMA -->
                <button
                    type="button"
                    id="returnConfirmButton"
                    class="flex-1 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition"
                >

                    Yakin Terima

                </button>


            </div>

        </div>

    </div>

</div>


<!-- ===================================================== -->
<!-- JAVASCRIPT MODAL -->
<!-- ===================================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    const returnModal =
        document.getElementById('returnModal');

    const returnCancelButton =
        document.getElementById('returnCancelButton');

    const returnConfirmButton =
        document.getElementById('returnConfirmButton');


    let activeReturnForm = null;


    /*
    |--------------------------------------------------------------------------
    | MENUTUP MODAL
    |--------------------------------------------------------------------------
    */

    function closeReturnModal() {

        returnModal.classList.add('hidden');

        returnModal.classList.remove('flex');

        activeReturnForm = null;

    }


    /*
    |--------------------------------------------------------------------------
    | KLIK TERIMA PENGEMBALIAN
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.return-button').forEach(button => {

        button.addEventListener('click', function () {

            activeReturnForm =
                this.closest('.return-form');


            if (!activeReturnForm) {
                return;
            }


            returnModal.classList.remove('hidden');

            returnModal.classList.add('flex');

        });

    });


    /*
    |--------------------------------------------------------------------------
    | TOMBOL BATAL
    |--------------------------------------------------------------------------
    */

    returnCancelButton.addEventListener('click', function () {

        closeReturnModal();

    });


    /*
    |--------------------------------------------------------------------------
    | TOMBOL YAKIN TERIMA
    |--------------------------------------------------------------------------
    */

    returnConfirmButton.addEventListener('click', function () {

        if (activeReturnForm) {

            activeReturnForm.submit();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | KLIK DI LUAR MODAL
    |--------------------------------------------------------------------------
    */

    returnModal.addEventListener('click', function (event) {

        if (event.target === returnModal) {

            closeReturnModal();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | TOMBOL ESC
    |--------------------------------------------------------------------------
    */

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            closeReturnModal();

        }

    });

});

</script>

@endsection