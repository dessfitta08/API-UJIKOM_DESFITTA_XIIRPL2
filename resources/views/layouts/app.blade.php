<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Panel Petugas / Admin</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100 font-sans antialiased">

<div class="flex h-screen bg-gray-100">

    <!-- ====================================================== -->
    <!-- SIDEBAR -->
    <!-- ====================================================== -->

    <aside class="w-64 bg-gray-900 text-white flex flex-col justify-between">

        <!-- BAGIAN ATAS SIDEBAR -->
        <div>

            <!-- HEADER SIDEBAR -->
            <div class="p-5 text-lg font-bold tracking-wider border-b border-gray-800">

                @if(auth()->check() && auth()->user()->role === 'admin')
                    PANEL ADMIN

                @elseif(auth()->check() && auth()->user()->role === 'petugas')
                    PANEL PETUGAS

                @elseif(auth()->check() && auth()->user()->role === 'peminjam')
                    PANEL PEMINJAM

                @else
                    PANEL
                @endif

            </div>


            <!-- ================================================== -->
            <!-- SIDEBAR ADMIN -->
            <!-- ================================================== -->

            @if(auth()->check() && auth()->user()->role === 'admin')

                <nav class="mt-4 px-2 space-y-1">

                    <!-- Dashboard -->
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="block px-4 py-2.5 rounded text-sm font-medium
                        {{ request()->routeIs('admin.dashboard')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Dashboard
                    </a>


                    <!-- Kelola User -->
                    <a
                        href="{{ route('admin.user.index') }}"
                        class="block px-4 py-2.5 rounded text-sm font-medium
                        {{ request()->routeIs('admin.user.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Kelola User
                    </a>


                    <!-- Kelola Kategori -->
                    <a
                        href="{{ route('admin.kategori.index') }}"
                        class="block px-4 py-2.5 rounded text-sm font-medium
                        {{ request()->routeIs('admin.kategori.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Kelola Kategori
                    </a>


                    <!-- Kelola Alat -->
                    <a
                        href="{{ route('admin.alat.index') }}"
                        class="block px-4 py-2.5 rounded text-sm font-medium
                        {{ request()->routeIs('admin.alat.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Kelola Alat
                    </a>


                    <!-- Kelola Peminjaman -->
                    <a
                        href="{{ route('admin.peminjaman.index') }}"
                        class="block px-4 py-2.5 rounded text-sm font-medium
                        {{ request()->routeIs('admin.peminjaman.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Kelola Peminjaman
                    </a>


                    <!-- Kelola Pengembalian -->
                    <a
                        href="{{ route('admin.pengembalian.index') }}"
                        class="block px-4 py-2.5 rounded text-sm font-medium
                        {{ request()->routeIs('admin.pengembalian.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Kelola Pengembalian
                    </a>


                    <!-- Log Aktivitas -->
                    <a
                        href="{{ route('admin.logaktivitas.index') }}"
                        class="block px-4 py-2.5 rounded text-sm font-medium
                        {{ request()->routeIs('admin.logaktivitas.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Kelola Aktivitas
                    </a>

                </nav>


            <!-- ================================================== -->
            <!-- SIDEBAR PETUGAS -->
            <!-- ================================================== -->

            @elseif(auth()->check() && auth()->user()->role === 'petugas')

                <nav class="mt-4 px-2 space-y-1">

                    <!-- Persetujuan Peminjaman -->
                    <a
                        href="{{ route('petugas.peminjaman.index') }}"
                        class="block px-4 py-2.5 rounded text-sm font-medium
                        {{ request()->routeIs('petugas.peminjaman.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Persetujuan Peminjaman
                    </a>


                    <!-- Pemantauan Pengembalian -->
                    <a
                        href="{{ route('petugas.pengembalian.index') }}"
                        class="block px-4 py-2.5 rounded text-sm font-medium
                        {{ request()->routeIs('petugas.pengembalian.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Pemantauan Pengembalian
                    </a>


                    <!-- Cetak Laporan -->
                    <a
                        href="{{ route('petugas.laporan.index') }}"
                        class="block px-4 py-2.5 rounded text-sm font-medium
                        {{ request()->routeIs('petugas.laporan.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Cetak Laporan
                    </a>

                </nav>


            <!-- ================================================== -->
            <!-- SIDEBAR PEMINJAM -->
            <!-- ================================================== -->

            @elseif(auth()->check() && auth()->user()->role === 'peminjam')

                <nav class="mt-4 px-2 space-y-1">

                    <!-- Katalog Alat -->
                    <a
                        href="{{ route('peminjam.katalog') }}"
                        class="block px-4 py-2.5 rounded text-sm font-medium
                        {{ request()->routeIs('peminjam.katalog')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Katalog Alat
                    </a>


                    <!-- Riwayat Peminjaman -->
                    <a
                        href="{{ route('peminjam.riwayat') }}"
                        class="block px-4 py-2.5 rounded text-sm font-medium
                        {{ request()->routeIs('peminjam.riwayat')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Riwayat Peminjaman
                    </a>

                </nav>

            @endif

        </div>


        <!-- ====================================================== -->
        <!-- PROFIL USER DI SIDEBAR BAWAH -->
        <!-- ====================================================== -->

        @if(auth()->check())

            <!-- ================================================== -->
            <!-- PROFIL ADMIN -->
            <!-- ================================================== -->

            @if(auth()->user()->role === 'admin')

                <a
                    href="{{ route('admin.profile') }}"
                    class="block p-4 border-t border-gray-800 bg-gray-900 hover:bg-gray-800 transition cursor-pointer"
                >

                    <div class="flex items-center space-x-3">

                        @if(auth()->user()->foto_profile)

                            <img
                                src="{{ asset(auth()->user()->foto_profile) }}"
                                alt="Foto Profil"
                                class="w-10 h-10 rounded-full object-cover shrink-0"
                            >

                        @else

                            <img
                                src="{{ asset('images/default-user.jpg') }}"
                                alt="Avatar"
                                class="w-10 h-10 rounded-full object-cover shrink-0"
                            >

                        @endif


                        <div class="overflow-hidden">

                            <span class="block text-xs text-gray-400">
                                Logged in as:
                            </span>

                            <span class="block text-sm font-semibold text-white truncate">
                                {{ auth()->user()->name }}
                            </span>

                            <span class="block text-xs text-gray-400 capitalize">
                                {{ auth()->user()->role }}
                            </span>

                        </div>

                    </div>

                </a>


            <!-- ================================================== -->
            <!-- PROFIL PETUGAS -->
            <!-- ================================================== -->

            @elseif(auth()->user()->role === 'petugas')

                <a
                    href="{{ route('petugas.profile') }}"
                    class="block p-4 border-t border-gray-800 bg-gray-900 hover:bg-gray-800 transition cursor-pointer"
                >

                    <div class="flex items-center space-x-3">

                        @if(auth()->user()->foto_profile)

                            <img
                                src="{{ asset(auth()->user()->foto_profile) }}"
                                alt="Foto Profil"
                                class="w-10 h-10 rounded-full object-cover shrink-0"
                            >

                        @else

                            <img
                                src="{{ asset('images/default-user.jpg') }}"
                                alt="Avatar"
                                class="w-10 h-10 rounded-full object-cover shrink-0"
                            >

                        @endif


                        <div class="overflow-hidden">

                            <span class="block text-xs text-gray-400">
                                Logged in as:
                            </span>

                            <span class="block text-sm font-semibold text-white truncate">
                                {{ auth()->user()->name }}
                            </span>

                            <span class="block text-xs text-gray-400 capitalize">
                                {{ auth()->user()->role }}
                            </span>

                        </div>

                    </div>

                </a>


            <!-- ================================================== -->
            <!-- PROFIL PEMINJAM -->
            <!-- ================================================== -->

            @elseif(auth()->user()->role === 'peminjam')

                <a
                    href="{{ route('peminjam.profile') }}"
                    class="block p-4 border-t border-gray-800 bg-gray-900 hover:bg-gray-800 transition cursor-pointer"
                >

                    <div class="flex items-center space-x-3">

                        @if(auth()->user()->foto_profile)

                            <img
                                src="{{ asset(auth()->user()->foto_profile) }}"
                                alt="Foto Profil"
                                class="w-10 h-10 rounded-full object-cover shrink-0"
                            >

                        @else

                            <img
                                src="{{ asset('images/default-user.jpg') }}"
                                alt="Avatar"
                                class="w-10 h-10 rounded-full object-cover shrink-0"
                            >

                        @endif


                        <div class="overflow-hidden">

                            <span class="block text-xs text-gray-400">
                                Logged in as:
                            </span>

                            <span class="block text-sm font-semibold text-white truncate">
                                {{ auth()->user()->name }}
                            </span>

                            <span class="block text-xs text-gray-400 capitalize">
                                {{ auth()->user()->role }}
                            </span>

                        </div>

                    </div>

                </a>

            @endif

        @endif

    </aside>


    <!-- ====================================================== -->
    <!-- KONTEN UTAMA -->
    <!-- ====================================================== -->

    <div class="flex-1 flex flex-col overflow-hidden">


        <!-- ================================================== -->
        <!-- NAVBAR ATAS -->
        <!-- ================================================== -->

        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6">

            <!-- JUDUL SESUAI ROLE / HALAMAN -->
            <h1 class="text-lg font-semibold text-gray-800">

                @if(auth()->check() && auth()->user()->role === 'peminjam')

                    @if(request()->routeIs('peminjam.katalog'))
                        Katalog Alat

                    @elseif(request()->routeIs('peminjam.riwayat'))
                        Riwayat Peminjaman

                    @elseif(request()->routeIs('peminjam.profile'))
                        Profil Saya

                    @else
                        Sistem Peminjaman
                    @endif


                @elseif(auth()->check() && auth()->user()->role === 'petugas')

                    @if(request()->routeIs('petugas.peminjaman.*'))
                        Persetujuan Peminjaman

                    @elseif(request()->routeIs('petugas.pengembalian.*'))
                        Pemantauan & Proses Pengembalian Alat

                    @elseif(request()->routeIs('petugas.laporan.*'))
                        Cetak Laporan

                    @elseif(request()->routeIs('petugas.profile'))
                        Profil Saya

                    @else
                        Panel Petugas
                    @endif


                @elseif(auth()->check() && auth()->user()->role === 'admin')

                    @if(request()->routeIs('admin.dashboard'))
                        Dashboard

                    @elseif(request()->routeIs('admin.user.*'))
                        Manajemen Pengguna Sistem

                    @elseif(request()->routeIs('admin.kategori.*'))
                        Manajemen Kategori

                    @elseif(request()->routeIs('admin.alat.*'))
                        Manajemen Alat

                    @elseif(request()->routeIs('admin.peminjaman.*'))
                        Manajemen Peminjaman

                    @elseif(request()->routeIs('admin.pengembalian.*'))
                        Manajemen Pengembalian

                    @elseif(request()->routeIs('admin.logaktivitas.*'))
                        Log Aktivitas

                    @elseif(request()->routeIs('admin.profile'))
                        Profil Saya

                    @else
                        Panel Admin
                    @endif


                @else

                    Sistem Peminjaman

                @endif

            </h1>


            <!-- ================================================== -->
            <!-- TOMBOL LOGOUT -->
            <!-- ================================================== -->

            <form action="{{ route('logout') }}" method="POST">

                @csrf

                <button
                    type="submit"
                    class="bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded shadow"
                >
                    Logout
                </button>

            </form>

        </header>


        <!-- ====================================================== -->
        <!-- ISI KONTEN -->
        <!-- ====================================================== -->

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">

            @yield('content')

        </main>

    </div>

</div>


<!-- ====================================================== -->
<!-- MODAL KONFIRMASI PENGHAPUSAN GLOBAL -->
<!-- ====================================================== -->

<div
    id="deleteModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4"
>

    <div
        class="w-full max-w-md bg-white rounded-lg shadow-xl border border-gray-200"
        onclick="event.stopPropagation()"
    >

        <!-- HEADER MODAL -->
        <div class="px-6 py-4 border-b border-gray-200">

            <h2 class="text-lg font-semibold text-gray-800">
                Konfirmasi Penghapusan
            </h2>

        </div>


        <!-- ISI MODAL -->
        <div class="px-6 py-5">

            <p
                id="deleteModalMessage"
                class="text-sm text-gray-600 leading-relaxed"
            >
                Apakah yakin ingin menghapus data ini?
            </p>

        </div>


        <!-- TOMBOL MODAL -->
        <div class="px-6 pb-5">

            <div class="flex items-center justify-between gap-3">

                <!-- TOMBOL BATAL / BELUM DIKEMBALIKAN -->
                <button
                    type="button"
                    id="deleteCancelButton"
                    class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition"
                >
                    Batal
                </button>


                <!-- TOMBOL HAPUS -->
                <button
                    type="button"
                    id="deleteConfirmButton"
                    class="flex-1 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition"
                >
                    Yakin Hapus
                </button>

            </div>

        </div>

    </div>

</div>


<!-- ====================================================== -->
<!-- SCRIPT MODAL HAPUS -->
<!-- ====================================================== -->

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const deleteModal =
            document.getElementById('deleteModal');

        const deleteMessage =
            document.getElementById('deleteModalMessage');

        const deleteCancelButton =
            document.getElementById('deleteCancelButton');

        const deleteConfirmButton =
            document.getElementById('deleteConfirmButton');

        let activeDeleteForm = null;


        /*
        |--------------------------------------------------------------------------
        | MEMBUKA MODAL
        |--------------------------------------------------------------------------
        */

        document.addEventListener('click', function (event) {

            const deleteButton =
                event.target.closest('.delete-button');

            if (!deleteButton) {
                return;
            }

            const form =
                deleteButton.closest('.delete-form');

            if (!form) {
                return;
            }

            activeDeleteForm = form;


            /*
            |--------------------------------------------------------------------------
            | PESAN MODAL
            |--------------------------------------------------------------------------
            */

            const message =
                form.dataset.deleteMessage ||
                'Apakah yakin ingin menghapus data ini?';

            deleteMessage.textContent = message;


            /*
            |--------------------------------------------------------------------------
            | JENIS DATA
            |--------------------------------------------------------------------------
            */

            const deleteType =
                form.dataset.deleteType || 'default';


            /*
            |--------------------------------------------------------------------------
            | KHUSUS PEMINJAMAN
            |--------------------------------------------------------------------------
            */

            if (deleteType === 'peminjaman') {

                deleteCancelButton.textContent =
                    'Belum Dikembalikan';

                deleteCancelButton.classList.remove(
                    'bg-gray-500',
                    'hover:bg-gray-600'
                );

                deleteCancelButton.classList.add(
                    'bg-emerald-500',
                    'hover:bg-emerald-600'
                );

            }

            /*
            |--------------------------------------------------------------------------
            | DATA LAIN
            |--------------------------------------------------------------------------
            */

            else {

                deleteCancelButton.textContent =
                    'Batal';

                deleteCancelButton.classList.remove(
                    'bg-emerald-500',
                    'hover:bg-emerald-600'
                );

                deleteCancelButton.classList.add(
                    'bg-emerald-500',
                    'hover:bg-emerald-600'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN MODAL
            |--------------------------------------------------------------------------
            */

            deleteModal.classList.remove('hidden');

            deleteModal.classList.add('flex');

        });


        /*
        |--------------------------------------------------------------------------
        | TOMBOL BATAL / BELUM DIKEMBALIKAN
        |--------------------------------------------------------------------------
        */

        deleteCancelButton.addEventListener('click', function () {

            closeDeleteModal();

        });


        /*
        |--------------------------------------------------------------------------
        | TOMBOL YAKIN HAPUS
        |--------------------------------------------------------------------------
        */

        deleteConfirmButton.addEventListener('click', function () {

            if (activeDeleteForm) {

                activeDeleteForm.submit();

            }

        });


        /*
        |--------------------------------------------------------------------------
        | KLIK AREA GELAP DI LUAR MODAL
        |--------------------------------------------------------------------------
        */

        deleteModal.addEventListener('click', function (event) {

            if (event.target === deleteModal) {

                closeDeleteModal();

            }

        });


        /*
        |--------------------------------------------------------------------------
        | TOMBOL ESC
        |--------------------------------------------------------------------------
        */

        document.addEventListener('keydown', function (event) {

            if (
                event.key === 'Escape' &&
                !deleteModal.classList.contains('hidden')
            ) {

                closeDeleteModal();

            }

        });


        /*
        |--------------------------------------------------------------------------
        | FUNGSI MENUTUP MODAL
        |--------------------------------------------------------------------------
        */

        function closeDeleteModal() {

            deleteModal.classList.add('hidden');

            deleteModal.classList.remove('flex');

            activeDeleteForm = null;

        }

    });

</script>


</body>

</html>