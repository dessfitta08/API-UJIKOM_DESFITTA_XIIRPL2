@extends('layouts.app')

@section('title', 'Persetujuan Peminjaman - Dashboard Petugas')
@section('header-title', 'Daftar Pengajuan Peminjaman Alat')

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


    {{-- ========================================================= --}}
    {{-- KONTEN UTAMA --}}
    {{-- ========================================================= --}}

    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">


        {{-- ===================================================== --}}
        {{-- HEADER ATAS --}}
        {{-- ===================================================== --}}

        <div class="p-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center flex-wrap gap-4">

            <h3 class="text-lg font-bold text-gray-800">
                Menunggu Verifikasi Persetujuan
            </h3>

            <form
                action="{{ route('petugas.peminjaman.index') }}"
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
                        href="{{ route('petugas.peminjaman.index') }}"
                        class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-2 text-sm rounded-lg flex items-center transition"
                    >
                        Reset
                    </a>

                @endif

            </form>

        </div>


        {{-- ===================================================== --}}
        {{-- TABEL --}}
        {{-- ===================================================== --}}

        <div class="overflow-x-auto">

            <table class="w-full text-sm border-collapse">

                {{-- HEADER TABEL SAJA YANG BERWARNA --}}

                <thead>

                    <tr class="bg-slate-800 text-white">

                        <th class="py-3 px-4 text-left font-semibold whitespace-nowrap">
                            Peminjam
                        </th>

                        <th class="py-3 px-4 text-left font-semibold whitespace-nowrap">
                            Tanggal Pinjam
                        </th>

                        <th class="py-3 px-4 text-left font-semibold whitespace-nowrap">
                            Rencana Kembali
                        </th>

                        <th class="py-3 px-4 text-left font-semibold">
                            Detail Alat
                        </th>

                        <th class="py-3 px-4 text-center font-semibold whitespace-nowrap">
                            Aksi
                        </th>

                    </tr>

                </thead>


                {{-- ISI TABEL --}}

                <tbody class="text-gray-700">

                    @forelse($peminjamans as $item)

                        <tr class="hover:bg-gray-50 transition align-top">


                            {{-- PEMINJAM --}}

                            <td class="py-3 px-4 border-b text-left font-medium text-gray-800">
                                {{ $item->user->name ?? 'User Dihapus' }}
                            </td>


                            {{-- TANGGAL PINJAM --}}

                            <td class="py-3 px-4 border-b text-left whitespace-nowrap">
                                {{ $item->tgl_pinjam }}
                            </td>


                            {{-- RENCANA KEMBALI --}}

                            <td class="py-3 px-4 border-b text-left whitespace-nowrap">
                                {{ $item->tgl_kembali_plan }}
                            </td>


                            {{-- DETAIL ALAT --}}

                            <td class="py-3 px-4 border-b text-left">

                                <ul class="list-disc list-inside space-y-1 text-xs">

                                    @foreach($item->detailPinjams as $detail)

                                        <li>

                                            <span class="font-semibold text-gray-800">
                                                {{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}
                                            </span>

                                            <span class="text-gray-600">
                                                (Jumlah: {{ $detail->jumlah }})
                                            </span>

                                        </li>

                                    @endforeach

                                </ul>

                            </td>


                            {{-- AKSI --}}

                            <td class="py-3 px-4 border-b text-center align-middle">

                                @if(in_array(strtolower($item->status), ['diajukan']))

                                    <div class="flex flex-col items-center justify-center gap-2">


                                        {{-- SETUJUI --}}

                                        <form
                                            action="{{ route('petugas.peminjaman.setujui', $item->id) }}"
                                            method="POST"
                                            class="approval-form w-full max-w-[110px]"
                                            data-action-type="setujui"
                                        >

                                            @csrf

                                            <button
                                                type="button"
                                                class="approval-button w-full bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg text-xs font-semibold transition shadow-sm"
                                            >
                                                Setujui
                                            </button>

                                        </form>


                                        {{-- TOLAK --}}

                                        <form
                                            action="{{ route('petugas.peminjaman.tolak', $item->id) }}"
                                            method="POST"
                                            class="approval-form w-full max-w-[110px]"
                                            data-action-type="tolak"
                                        >

                                            @csrf

                                            <button
                                                type="button"
                                                class="approval-button w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-xs font-semibold transition shadow-sm"
                                            >
                                                Tolak
                                            </button>

                                        </form>

                                    </div>

                                @else

                                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded">
                                        {{ ucfirst($item->status) }}
                                    </span>

                                @endif

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="py-8 px-4 border-b text-center text-gray-500"
                            >
                                Tidak ada pengajuan peminjaman baru.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ===================================================== --}}
        {{-- PAGINATION --}}
        {{-- ===================================================== --}}

        @if($peminjamans->hasPages())

            <div class="px-5 py-4 border-t border-gray-200">

                {{ $peminjamans->links() }}

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- MODAL KONFIRMASI PERSETUJUAN / PENOLAKAN --}}
    {{-- ========================================================= --}}

    <div
        id="approvalModal"
        class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/40 px-4"
    >

        <div
            class="w-full max-w-md bg-white rounded-lg shadow-xl border border-gray-200"
            onclick="event.stopPropagation()"
        >

            {{-- HEADER MODAL --}}

            <div class="px-6 py-4 border-b border-gray-200">

                <h2
                    id="approvalModalTitle"
                    class="text-lg font-semibold text-gray-800"
                >
                    Konfirmasi
                </h2>

            </div>


            {{-- ISI MODAL --}}

            <div class="px-6 py-5">

                <p
                    id="approvalModalMessage"
                    class="text-sm text-gray-600 leading-relaxed"
                >
                    Apakah yakin ingin melakukan tindakan ini?
                </p>

            </div>


            {{-- TOMBOL MODAL --}}

            <div class="px-6 pb-5">

                <div class="flex items-center justify-between gap-3">

                    {{-- BATAL --}}

                    <button
                        type="button"
                        id="approvalCancelButton"
                        class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition"
                    >
                        Batal
                    </button>


                    {{-- KONFIRMASI --}}

                    <button
                        type="button"
                        id="approvalConfirmButton"
                        class="flex-1 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition"
                    >
                        Yakin
                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- JAVASCRIPT MODAL --}}
    {{-- ========================================================= --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const approvalModal =
                document.getElementById('approvalModal');

            const approvalModalTitle =
                document.getElementById('approvalModalTitle');

            const approvalModalMessage =
                document.getElementById('approvalModalMessage');

            const approvalCancelButton =
                document.getElementById('approvalCancelButton');

            const approvalConfirmButton =
                document.getElementById('approvalConfirmButton');

            let activeApprovalForm = null;


            /*
            |--------------------------------------------------------------------------
            | BUKA MODAL
            |--------------------------------------------------------------------------
            */

            document.querySelectorAll('.approval-button').forEach(button => {

                button.addEventListener('click', function () {

                    activeApprovalForm =
                        this.closest('.approval-form');

                    if (!activeApprovalForm) {
                        return;
                    }

                    const actionType =
                        activeApprovalForm.dataset.actionType;


                    if (actionType === 'setujui') {

                        approvalModalTitle.textContent =
                            'Konfirmasi Persetujuan';

                        approvalModalMessage.textContent =
                            'Apakah yakin ingin menyetujui peminjaman alat ini?';

                        approvalConfirmButton.textContent =
                            'Yakin Setujui';

                    } else {

                        approvalModalTitle.textContent =
                            'Konfirmasi Penolakan';

                        approvalModalMessage.textContent =
                            'Apakah yakin ingin menolak pengajuan peminjaman ini?';

                        approvalConfirmButton.textContent =
                            'Yakin Tolak';

                    }


                    approvalModal.classList.remove('hidden');

                    approvalModal.classList.add('flex');

                });

            });


            /*
            |--------------------------------------------------------------------------
            | TOMBOL BATAL
            |--------------------------------------------------------------------------
            */

            approvalCancelButton.addEventListener('click', function () {

                closeApprovalModal();

            });


            /*
            |--------------------------------------------------------------------------
            | TOMBOL KONFIRMASI
            |--------------------------------------------------------------------------
            */

            approvalConfirmButton.addEventListener('click', function () {

                if (activeApprovalForm) {

                    activeApprovalForm.submit();

                }

            });


            /*
            |--------------------------------------------------------------------------
            | KLIK DI LUAR MODAL
            |--------------------------------------------------------------------------
            */

            approvalModal.addEventListener('click', function (event) {

                if (event.target === approvalModal) {

                    closeApprovalModal();

                }

            });


            /*
            |--------------------------------------------------------------------------
            | TOMBOL ESCAPE
            |--------------------------------------------------------------------------
            */

            document.addEventListener('keydown', function (event) {

                if (event.key === 'Escape') {

                    closeApprovalModal();

                }

            });


            /*
            |--------------------------------------------------------------------------
            | FUNGSI TUTUP MODAL
            |--------------------------------------------------------------------------
            */

            function closeApprovalModal() {

                approvalModal.classList.add('hidden');

                approvalModal.classList.remove('flex');

                activeApprovalForm = null;

            }

        });

    </script>

@endsection