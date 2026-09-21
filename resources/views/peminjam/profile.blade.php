@extends('layouts.app')

@section('title', 'Profil Peminjam')
@section('header-title', 'Profil Saya')

@section('content')

<div class="max-w-4xl mx-auto">

```
<div class="bg-white rounded-xl shadow overflow-hidden">

    <!-- HEADER -->
    <div class="bg-gray-800 text-white px-6 py-5">
        <h2 class="text-xl font-bold">Profil Saya</h2>
        <p class="text-sm text-gray-200 mt-1">
            Informasi akun pengguna yang sedang login
        </p>
    </div>

    <!-- CONTENT -->
    <div class="p-6">

        {{-- ========================= --}}
        {{-- PESAN SUKSES --}}
        {{-- ========================= --}}
        @if(session('success'))
            <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif


        {{-- ========================= --}}
        {{-- PESAN ERROR --}}
        {{-- ========================= --}}
        @if(session('error'))
            <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif


        {{-- ========================= --}}
        {{-- ERROR VALIDASI --}}
        {{-- ========================= --}}
        @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">

                <p class="font-semibold mb-2">
                    Terjadi kesalahan:
                </p>

                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif


        <form action="{{ route('peminjam.profile.update') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- ========================= -->
                <!-- BAGIAN FOTO -->
                <!-- ========================= -->
                <div>

                    <!-- FOTO PROFIL -->
                    <div class="flex justify-center mb-6">

                        @if($user->foto_profile)

                            <img
                                src="{{ asset($user->foto_profile) }}"
                                alt="Foto Profil"
                                class="w-40 h-40 rounded-full object-cover border-4 border-gray-200 shadow"
                            >

                        @else

                            <div class="w-40 h-40 rounded-full bg-gray-200 flex items-center justify-center border-4 border-gray-300">

                                <span class="text-gray-500 text-4xl font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>

                            </div>

                        @endif

                    </div>


                    <!-- INPUT FOTO -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Profil
                        </label>

                        <div class="flex items-center gap-3">

                            <label
                                for="foto_profile"
                                class="bg-gray-800 text-white px-4 py-2 rounded-lg cursor-pointer hover:bg-gray-700 transition">
                                Browse...
                            </label>

                            <span
                                id="file-name"
                                class="text-sm text-gray-500 truncate">
                                No file selected.
                            </span>

                            <input
                                type="file"
                                name="foto_profile"
                                id="foto_profile"
                                accept=".jpg,.jpeg,.png"
                                class="hidden"
                            >

                        </div>

                        <p class="text-xs text-gray-500 mt-2">
                            Format: JPG, JPEG, PNG. Maksimal 2 MB.
                        </p>

                    </div>


                    <!-- TOMBOL SIMPAN -->
                    <button
                        type="submit"
                        class="w-full mt-4 bg-gray-800 text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                        Simpan Perubahan
                    </button>

                </div>


                <!-- ========================= -->
                <!-- DATA USER -->
                <!-- ========================= -->
                <div class="space-y-5">

                    <!-- NAMA -->
                    <div>

                        <label class="block text-sm text-gray-700 mb-2">
                            Nama
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-400"
                            required
                        >

                    </div>


                    <!-- EMAIL -->
                    <div>

                        <label class="block text-sm text-gray-700 mb-2">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-400"
                            required
                        >

                    </div>


                    <!-- ROLE -->
                    <div>

                        <label class="block text-sm text-gray-700 mb-2">
                            Role
                        </label>

                        <div class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50">

                            <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                                PEMINJAM
                            </span>

                        </div>

                    </div>


                    <!-- NO HP -->
                    <div>

                        <label class="block text-sm text-gray-700 mb-2">
                            No. HP
                        </label>

                        <input
                            type="text"
                            name="no_hp"
                            value="{{ old('no_hp', $user->no_hp) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        >

                    </div>


                    <!-- ALAMAT -->
                    <div>

                        <label class="block text-sm text-gray-700 mb-2">
                            Alamat
                        </label>

                        <textarea
                            name="alamat"
                            rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        >{{ old('alamat', $user->alamat) }}</textarea>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>
```

</div>

<!-- ========================= -->

<!-- SCRIPT NAMA FILE -->

<!-- ========================= -->

<script>

    const fotoInput = document.getElementById('foto_profile');
    const fileName = document.getElementById('file-name');

    if (fotoInput && fileName) {

        fotoInput.addEventListener('change', function () {

            if (this.files.length > 0) {

                fileName.textContent = this.files[0].name;

            } else {

                fileName.textContent = 'No file selected.';

            }

        });

    }

</script>

@endsection
