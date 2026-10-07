@extends('layouts.app')

@section('title', 'Dashboard Admin - Sistem Peminjaman')
@section('header-title', 'Ringkasan Aktivitas Sistem')

@section('content')

    <!-- Alert Selamat Datang -->
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg shadow-sm">
        Selamat datang,
        <strong class="font-semibold">
            {{ auth()->user()->name }}
        </strong>!

        Anda login sebagai hak akses
        <span class="uppercase font-bold text-emerald-900">
            {{ auth()->user()->role }}
        </span>.
    </div>


    <!-- ========================================================= -->
    <!-- TOTAL DATA -->
    <!-- ========================================================= -->

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">

        <!-- TOTAL USER -->
        <div class="bg-blue-500 rounded-lg shadow-sm p-5 text-white">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium">
                        Total User
                    </p>

                    <p class="text-2xl font-bold mt-1">
                        {{ $totalUser }}
                    </p>
                </div>

                <div class="w-11 h-11 bg-white/20 rounded-full flex items-center justify-center">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 19a4 4 0 00-8 0m4-8a4 4 0 100-8 4 4 0 000 8zm9 8a4 4 0 00-3-3.87M17 3.13a4 4 0 010 7.75"
                        />

                    </svg>

                </div>

            </div>
        </div>


        <!-- TOTAL KATEGORI -->
        <div class="bg-purple-500 rounded-lg shadow-sm p-5 text-white">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium">
                        Total Kategori
                    </p>

                    <p class="text-2xl font-bold mt-1">
                        {{ $totalKategori }}
                    </p>
                </div>

                <div class="w-11 h-11 bg-white/20 rounded-full flex items-center justify-center">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 6h16M4 12h16M4 18h16"
                        />

                    </svg>

                </div>

            </div>
        </div>


        <!-- TOTAL ALAT -->
        <div class="bg-orange-500 rounded-lg shadow-sm p-5 text-white">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium">
                        Total Alat
                    </p>

                    <p class="text-2xl font-bold mt-1">
                        {{ $totalAlat }}
                    </p>
                </div>

                <div class="w-11 h-11 bg-white/20 rounded-full flex items-center justify-center">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 5h6M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"
                        />

                    </svg>

                </div>

            </div>
        </div>


        <!-- TOTAL PEMINJAMAN -->
        <div class="bg-emerald-500 rounded-lg shadow-sm p-5 text-white">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium">
                        Total Peminjaman
                    </p>

                    <p class="text-2xl font-bold mt-1">
                        {{ $totalPeminjaman }}
                    </p>
                </div>

                <div class="w-11 h-11 bg-white/20 rounded-full flex items-center justify-center">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 4v16m8-8H4"
                        />

                    </svg>

                </div>

            </div>
        </div>


        <!-- TOTAL PENGEMBALIAN -->
        <div class="bg-red-500 rounded-lg shadow-sm p-5 text-white">
            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium">
                        Total Pengembalian
                    </p>

                    <p class="text-2xl font-bold mt-1">
                        {{ $totalPengembalian }}
                    </p>
                </div>

                <div class="w-11 h-11 bg-white/20 rounded-full flex items-center justify-center">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 13l4 4L19 7"
                        />

                    </svg>

                </div>

            </div>
        </div>

    </div>


    <!-- ========================================================= -->
    <!-- TABEL LOG AKTIVITAS -->
    <!-- ========================================================= -->

    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">

        <!-- Judul -->
        <div class="p-5 border-b border-gray-200 bg-gray-50">

            <h3 class="text-lg font-bold text-gray-800">
                Log Aktivitas Terbaru
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                Menampilkan aktivitas terbaru dalam sistem.
            </p>

        </div>


        <!-- Tabel -->
        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse">

                <!-- HEADER TABEL -->
                <thead>

                    <tr class="bg-slate-800 text-white text-sm uppercase tracking-wider">

                        <th class="py-3 px-4 border-b">
                            Waktu
                        </th>

                        <th class="py-3 px-4 border-b">
                            User
                        </th>

                        <th class="py-3 px-4 border-b">
                            Aktivitas
                        </th>

                    </tr>

                </thead>


                <!-- ISI TABEL -->
                <tbody class="text-gray-700 text-sm">

                    @forelse($logs as $log)

                        <tr class="hover:bg-gray-50 transition">

                            <td class="py-3 px-4 border-b">
                                {{ $log->created_at }}
                            </td>

                            <td class="py-3 px-4 border-b font-medium text-gray-900">
                                {{ $log->user->name ?? 'Sistem' }}
                            </td>

                            <td class="py-3 px-4 border-b">
                                {{ $log->aktivitas }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="3"
                                class="py-4 text-center text-gray-500">

                                Belum ada log aktivitas.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection