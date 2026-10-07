@extends('layouts.app')

@section('title', 'Proses Pengembalian')

@section('header-title', 'Proses Pengembalian')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

        {{-- HEADER --}}
        <div class="mb-6">

            <h2 class="text-2xl font-bold text-gray-800">
                Proses Pengembalian
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Catat pengembalian alat dan denda.
            </p>

        </div>


        {{-- ERROR VALIDASI --}}
        @if ($errors->any())

            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-5">

                <strong>Terjadi kesalahan:</strong>

                <ul class="list-disc ml-5 mt-2 text-sm">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- PESAN ERROR DARI CONTROLLER --}}
        @if(session('error'))

            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-5">

                {{ session('error') }}

            </div>

        @endif


        {{-- FORM --}}
        <form
            action="{{ route('admin.pengembalian.store') }}"
            method="POST"
            id="formPengembalian"
        >

            @csrf


            {{-- ========================================================= --}}
            {{-- ID PEMINJAMAN --}}
            {{-- ========================================================= --}}

            <input
                type="hidden"
                name="peminjaman_id"
                value="{{ $peminjaman->id }}"
            >


            {{-- ========================================================= --}}
            {{-- INFORMASI PEMINJAMAN --}}
            {{-- ========================================================= --}}

            <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-6">

                <h3 class="font-bold text-gray-800 mb-4">
                    Informasi Peminjaman
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- PEMINJAM --}}
                    <div>

                        <p class="text-sm text-gray-500">
                            Peminjam
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $peminjaman->user->name ?? '-' }}
                        </p>

                    </div>


                    {{-- STATUS --}}
                    <div>

                        <p class="text-sm text-gray-500">
                            Status
                        </p>

                        <span class="inline-flex mt-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">

                            {{ ucfirst($peminjaman->status) }}

                        </span>

                    </div>


                    {{-- TANGGAL PINJAM --}}
                    <div>

                        <p class="text-sm text-gray-500">
                            Tanggal Pinjam
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $peminjaman->tgl_pinjam ?? '-' }}
                        </p>

                    </div>


                    {{-- JATUH TEMPO --}}
                    <div>

                        <p class="text-sm text-gray-500">
                            Jatuh Tempo
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $peminjaman->tgl_kembali_plan ?? '-' }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- DAFTAR ALAT --}}
            {{-- ========================================================= --}}

            <div class="mb-6">

                <h3 class="font-bold text-gray-800 mb-3">
                    Detail Alat
                </h3>

                <div class="border border-gray-200 rounded-lg overflow-hidden">

                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">

                            <thead class="bg-gray-800 text-white">

                                <tr>

                                    <th class="text-left px-4 py-3">
                                        Alat
                                    </th>

                                    <th class="text-center px-4 py-3">
                                        Jumlah
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($peminjaman->detailPinjams as $detail)

                                    <tr class="border-b last:border-b-0">

                                        <td class="px-4 py-3 text-gray-800">

                                            {{ $detail->alat->nama_alat ?? '-' }}

                                        </td>

                                        <td class="px-4 py-3 text-center text-gray-700">

                                            {{ $detail->jumlah }} unit

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="2"
                                            class="px-4 py-5 text-center text-gray-500"
                                        >
                                            Tidak ada detail alat.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- TANGGAL KEMBALI --}}
            {{-- ========================================================= --}}

            <div class="mb-6">

                <label
                    for="tgl_kembali"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Tanggal Kembali
                </label>

                <input
                    type="date"
                    name="tgl_kembali"
                    id="tgl_kembali"
                    value="{{ old('tgl_kembali', now()->format('Y-m-d')) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

                <p class="text-xs text-gray-500 mt-1">
                    Tanggal saat alat dikembalikan.
                </p>

            </div>


            {{-- ========================================================= --}}
            {{-- KONDISI KEMBALI --}}
            {{-- ========================================================= --}}

            <div class="mb-6">

                <label
                    for="kondisi_kembali"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Kondisi Umum Pengembalian
                </label>

                <select
                    name="kondisi_kembali"
                    id="kondisi_kembali"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >

                    <option value="">
                        -- Pilih Kondisi --
                    </option>

                    <option
                        value="Baik"
                        {{ old('kondisi_kembali') == 'Baik' ? 'selected' : '' }}
                    >
                        Baik
                    </option>

                    <option
                        value="Rusak Ringan"
                        {{ old('kondisi_kembali') == 'Rusak Ringan' ? 'selected' : '' }}
                    >
                        Rusak Ringan
                    </option>

                    <option
                        value="Rusak Berat"
                        {{ old('kondisi_kembali') == 'Rusak Berat' ? 'selected' : '' }}
                    >
                        Rusak Berat
                    </option>

                </select>

            </div>


            {{-- ========================================================= --}}
            {{-- DENDA --}}
            {{-- ========================================================= --}}

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-5 mb-6">

                <h3 class="font-bold text-gray-800 mb-2">
                    Denda Pengembalian
                </h3>

                <p class="text-sm text-gray-600 mb-4">
                    Masukkan nominal denda jika terdapat keterlambatan atau kerusakan.
                    Jika tidak ada denda, isi dengan Rp 0.
                </p>


                <label
                    for="denda"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Total Denda
                </label>

                <div class="relative">

                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600">
                        Rp
                    </span>

                    <input
                        type="text"
                        name="denda"
                        id="denda"
                        value="{{ old('denda', '0') }}"
                        inputmode="numeric"
                        autocomplete="off"
                        class="w-full border border-gray-300 rounded-lg pl-12 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >

                </div>

                <p class="text-xs text-gray-500 mt-2">
                    Contoh: Rp 20.000 ditulis sebagai 20000.
                </p>

            </div>


            {{-- ========================================================= --}}
            {{-- PETUGAS --}}
            {{-- ========================================================= --}}

            <div class="mb-6">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Petugas
                </label>

                <div class="w-full border border-gray-300 bg-gray-100 rounded-lg px-4 py-2.5 text-gray-700">

                    {{ auth()->user()->name }}

                    <span class="text-gray-500">
                        ({{ ucfirst(auth()->user()->role) }})
                    </span>

                </div>

                <p class="text-xs text-gray-500 mt-1">
                    Petugas otomatis menggunakan akun yang sedang login.
                </p>

            </div>


            {{-- ========================================================= --}}
            {{-- BUTTON --}}
            {{-- ========================================================= --}}

            <div class="flex gap-3">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition"
                >
                    Simpan Pengembalian
                </button>

                <a
                    href="{{ route('admin.pengembalian.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-lg font-medium transition"
                >
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>


{{-- ========================================================= --}}
{{-- FORMAT DENDA --}}
{{-- ========================================================= --}}

<script>

    const inputDenda = document.getElementById('denda');
    const formPengembalian = document.getElementById('formPengembalian');

    inputDenda.addEventListener('input', function () {

        let angka = this.value.replace(/\D/g, '');

        if (angka === '') {
            this.value = '0';
            return;
        }

        this.value = new Intl.NumberFormat('id-ID').format(
            parseInt(angka, 10)
        );

    });


    formPengembalian.addEventListener('submit', function () {

        inputDenda.value = inputDenda.value.replace(/\D/g, '');

    });

</script>

@endsection