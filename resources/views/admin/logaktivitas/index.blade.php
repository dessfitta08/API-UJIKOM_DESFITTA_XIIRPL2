@extends('layouts.app')

@section('title', 'Log Aktivitas')
@section('header-title', 'Log Aktivitas')

@section('content')

<div class="bg-white rounded-xl shadow-sm p-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>
            <h2 class="text-xl font-bold text-gray-800">
                Data Log Aktivitas
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Riwayat aktivitas pengguna dalam sistem
            </p>
        </div>

        <!-- SEARCH -->
        <form
            action="{{ route('admin.logaktivitas.index') }}"
            method="GET"
            class="flex w-full md:w-96"
        >

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama pengguna / aktivitas..."
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
                    href="{{ route('admin.logaktivitas.index') }}"
                    class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-2 text-sm rounded-lg flex items-center transition"
                >
                    Reset
                </a>
            @endif

        </form>

    </div>


    <!-- TABEL LOG AKTIVITAS -->
    <div class="overflow-x-auto">

        <table class="w-full text-sm text-left">

            <thead class="bg-gray-800 text-white">

                <tr>
                    <th class="px-4 py-3">
                        No
                    </th>

                    <th class="px-4 py-3">
                        Pengguna
                    </th>

                    <th class="px-4 py-3">
                        Aktivitas
                    </th>

                    <th class="px-4 py-3">
                        Waktu
                    </th>
                </tr>

            </thead>

            <tbody class="divide-y divide-gray-200">

                @forelse($logs as $log)

                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-3 text-gray-700">
                            {{ $logs->firstItem() + $loop->index }}
                        </td>

                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $log->user->name ?? 'User tidak ditemukan' }}
                        </td>

                        <td class="px-4 py-3 text-gray-700">
                            {{ $log->aktivitas }}
                        </td>

                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                            {{ $log->created_at->format('d-m-Y H:i') }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="4"
                            class="px-4 py-8 text-center text-gray-500"
                        >
                            Tidak ada data log aktivitas.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <!-- PAGINATION -->
    @if($logs->hasPages())

        <div class="mt-6">
            {{ $logs->links() }}
        </div>

    @endif

</div>

@endsection
