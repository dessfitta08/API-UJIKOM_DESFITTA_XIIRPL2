@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <!-- HEADER -->
        <div class="bg-gray-800 text-white px-6 py-5">
            <h2 class="text-xl font-bold">
                Profil Saya
            </h2>

            <p class="text-sm text-gray-300 mt-1">
                Informasi akun pengguna yang sedang login
            </p>
        </div>


        <!-- FORM PROFIL -->
        <form
            action="{{ route('petugas.profile.update') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">


                <!-- ================================================= -->
                <!-- BAGIAN FOTO -->
                <!-- ================================================= -->

                <div class="flex flex-col items-center">

                    <!-- FOTO PROFIL -->
                    @if($user->foto_profile)

                        <img
                            src="{{ asset($user->foto_profile) }}"
                            alt="Foto Profil"
                            class="w-40 h-40 rounded-full object-cover border-4 border-gray-200 shadow"
                            onerror="this.onerror=null; this.src='{{ asset('images/default-user.jpg') }}';"
                        >

                    @else

                        <img
                            src="{{ asset('images/default-user.jpg') }}"
                            alt="Foto Profil"
                            class="w-40 h-40 rounded-full object-cover border-4 border-gray-200 shadow"
                        >

                    @endif


                    <!-- INPUT FOTO -->
                    <div class="w-full mt-5">

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Profil
                        </label>

                        <div class="flex items-center gap-3">

                            <!-- BUTTON BROWSE -->
                            <label
                                for="foto_profile"
                                class="shrink-0 cursor-pointer bg-gray-800 hover:bg-gray-900 text-white font-semibold px-4 py-2.5 rounded-lg"
                            >
                                Browse...
                            </label>

                            <!-- NAMA FILE -->
                            <span
                                id="file-name"
                                class="text-sm text-gray-500 truncate"
                            >
                                No file selected.
                            </span>

                            <!-- INPUT FILE -->
                            <input
                                type="file"
                                name="foto_profile"
                                id="foto_profile"
                                accept="image/jpeg,image/png,image/jpg"
                                class="hidden"
                            >

                        </div>

                        @error('foto_profile')
                            <p class="text-red-500 text-sm mt-2">
                                {{ $message }}
                            </p>
                        @enderror

                        <p class="text-xs text-gray-500 mt-2">
                            Format: JPG, JPEG, PNG. Maksimal 2 MB.
                        </p>

                    </div>


                    <!-- TOMBOL SIMPAN -->
                    <button
                        type="submit"
                        class="mt-4 w-full bg-gray-800 hover:bg-gray-900 text-white font-semibold py-3 rounded-lg shadow-sm"
                    >
                        Simpan Perubahan
                    </button>

                </div>


                <!-- ================================================= -->
                <!-- DATA USER -->
                <!-- ================================================= -->

                <div class="space-y-5">

                    <!-- NAMA -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-400"
                            required
                        >

                        @error('name')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- EMAIL -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-400"
                            required
                        >

                        @error('email')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- ROLE -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Role
                        </label>

                        <div class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50">

                            <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold uppercase">
                                {{ $user->role }}
                            </span>

                        </div>

                    </div>


                    <!-- NO HP -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            No. HP
                        </label>

                        <input
                            type="text"
                            name="no_hp"
                            value="{{ old('no_hp', $user->no_hp) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        >

                        @error('no_hp')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- ALAMAT -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat
                        </label>

                        <textarea
                            name="alamat"
                            rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        >{{ old('alamat', $user->alamat) }}</textarea>

                        @error('alamat')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


<!-- ================================================= -->
<!-- JAVASCRIPT INPUT FOTO -->
<!-- ================================================= -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    const fotoInput = document.getElementById('foto_profile');
    const fileName = document.getElementById('file-name');

    if (fotoInput && fileName) {

        fotoInput.addEventListener('change', function () {

            if (this.files && this.files.length > 0) {
                fileName.textContent = this.files[0].name;
            } else {
                fileName.textContent = 'No file selected.';
            }

        });

    }

});
</script>

@endsection
