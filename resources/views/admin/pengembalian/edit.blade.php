@extends('layouts.app')

@section('title', 'Edit Pengembalian')

@section('header-title', 'Edit Pengembalian')

@section('content')

<div class="max-w-3xl ml-6">

    <div class="bg-white rounded-lg shadow p-6">

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Edit Pengembalian
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Perbarui data pengembalian alat
            </p>
        </div>


        {{-- ERROR --}}
        @if ($errors->any())

            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-5">

                <strong>Terjadi kesalahan:</strong>

                <ul class="list-disc ml-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif


        <form
            action="{{ route('admin.pengembalian.update', $pengembalian->id) }}"
            method="POST"
            id="formPengembalian"
        >

            @csrf
            @method('PUT')


            {{-- PEMINJAM --}}
            <div class="mb-5">

                <label class="block font-medium text-gray-700 mb-2">
                    Peminjam
                </label>

                <div class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 text-gray-700">

                    {{ $pengembalian->peminjaman->user->name ?? '-' }}

                </div>

            </div>


            {{-- TANGGAL KEMBALI --}}
            <div class="mb-5">

                <label class="block font-medium text-gray-700 mb-2">
                    Tanggal Kembali
                </label>

                <div class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 text-gray-700">

                    {{ $pengembalian->tgl_kembali
                        ? $pengembalian->tgl_kembali->format('Y-m-d')
                        : '-' }}

                </div>

            </div>


            {{-- KONDISI KEMBALI --}}
            <div class="mb-5">

                <label
                    for="kondisi_kembali"
                    class="block font-medium text-gray-700 mb-2"
                >
                    Kondisi Kembali
                </label>

                <select
                    name="kondisi_kembali"
                    id="kondisi_kembali"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2"
                    required
                >

                    <option
                        value="baik"
                        {{ old('kondisi_kembali', strtolower($pengembalian->kondisi_kembali)) == 'baik' ? 'selected' : '' }}
                    >
                        Baik
                    </option>

                    <option
                        value="rusak ringan"
                        {{ old('kondisi_kembali', strtolower($pengembalian->kondisi_kembali)) == 'rusak ringan' ? 'selected' : '' }}
                    >
                        Rusak Ringan
                    </option>

                    <option
                        value="rusak berat"
                        {{ old('kondisi_kembali', strtolower($pengembalian->kondisi_kembali)) == 'rusak berat' ? 'selected' : '' }}
                    >
                        Rusak Berat
                    </option>

                </select>

            </div>


            {{-- TOTAL DENDA --}}
            <div class="mb-5">

                <label
                    for="denda"
                    class="block font-medium text-gray-700 mb-2"
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
                        class="w-full border border-gray-300 rounded-lg pl-12 pr-4 py-2"
                        value="{{ old('denda', number_format($pengembalian->denda ?? 0, 0, ',', '.')) }}"
                        inputmode="numeric"
                        autocomplete="off"
                        required
                    >

                </div>

            </div>


            {{-- PETUGAS --}}
            <div class="mb-6">

                <label class="block font-medium text-gray-700 mb-2">
                    Petugas
                </label>

                <div class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 text-gray-700">

                    {{ $pengembalian->petugas->name ?? '-' }}

                </div>

            </div>


            {{-- BUTTON --}}
            <div class="flex justify-end gap-2">

                <a
                    href="{{ route('admin.pengembalian.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition"
                >
                    Kembali
                </a>

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


{{-- FORMAT DENDA --}}
<script>

    const inputDenda = document.getElementById('denda');
    const formPengembalian = document.getElementById('formPengembalian');


    // Format angka menjadi 20.000 saat diketik
    inputDenda.addEventListener('input', function () {

        let angka = this.value.replace(/\D/g, '');

        if (angka === '') {
            this.value = '';
            return;
        }

        this.value = new Intl.NumberFormat('id-ID').format(angka);

    });


    // Sebelum dikirim, hapus titik agar Laravel menerima 20000
    formPengembalian.addEventListener('submit', function () {

        inputDenda.value = inputDenda.value.replace(/\D/g, '');

    });

</script>

@endsection