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


        <!-- PESAN SUCCESS -->
        @if(session('success'))
            <div class="mx-6 mt-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif


        <!-- ERROR VALIDASI -->
        @if($errors->any())
            <div class="mx-6 mt-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <!-- FORM PROFIL -->
        <form
            action="{{ route('admin.profile.update') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')


            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">


                <!-- ================================================= -->
                <!-- FOTO PROFIL -->
                <!-- ================================================= -->

                <div class="flex flex-col items-center">

                    <!-- FOTO -->
                    @if(auth()->user()->foto_profile)

                        <img
                            src="{{ asset(auth()->user()->foto_profile) }}"
                            alt="Foto Profil"
                            class="w-40 h-40 rounded-full object-cover border-4 border-gray-200 shadow"
                        >

                    @else

                        <img
                            src="{{ asset('images/default-user.jpg') }}"
                            alt="Foto Profil"
                            class="w-40 h-40 rounded-full object-cover border-4 border-gray-200 shadow"
                        >

                    @endif


                    <!-- INPUT FOTO -->
                    <div class="mt-5 w-full">

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Profil
                        </label>

                        <input
                            type="file"
                            name="foto_profile"
                            accept="image/jpeg,image/png,image/jpg"
                            class="block w-full text-sm text-gray-700
                                   border border-gray-300 rounded-lg
                                   cursor-pointer
                                   file:mr-4
                                   file:py-2
                                   file:px-4
                                   file:rounded-lg
                                   file:border-0
                                   file:bg-gray-800
                                   file:text-white
                                   hover:file:bg-gray-900"
                        >

                        <p class="text-xs text-gray-500 mt-2">
                            Format: JPG, JPEG, PNG. Maksimal 2 MB.
                        </p>

                    </div>


                    <!-- TOMBOL SIMPAN -->
                    <button
                        type="submit"
                        class="mt-5 w-full bg-gray-800 hover:bg-gray-900
                               text-white font-medium py-2.5 rounded-lg
                               transition"
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
                            value="{{ old('name', auth()->user()->name) }}"
                            class="w-full border border-gray-300 rounded-lg
                                   px-4 py-3
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-gray-400"
                            required
                        >

                    </div>


                    <!-- EMAIL -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', auth()->user()->email) }}"
                            class="w-full border border-gray-300 rounded-lg
                                   px-4 py-3
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-gray-400"
                            required
                        >

                    </div>


                    <!-- ROLE -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Role
                        </label>

                        <div class="w-full border border-gray-300 rounded-lg
                                    px-4 py-3 bg-gray-50">

                            @if(auth()->user()->role === 'admin')

                                <span class="inline-block
                                             bg-purple-100
                                             text-purple-700
                                             px-3 py-1
                                             rounded-full
                                             text-sm
                                             font-semibold
                                             uppercase">
                                    Admin
                                </span>

                            @elseif(auth()->user()->role === 'petugas')

                                <span class="inline-block
                                             bg-blue-100
                                             text-blue-700
                                             px-3 py-1
                                             rounded-full
                                             text-sm
                                             font-semibold
                                             uppercase">
                                    Petugas
                                </span>

                            @else

                                <span class="inline-block
                                             bg-gray-100
                                             text-gray-700
                                             px-3 py-1
                                             rounded-full
                                             text-sm
                                             font-semibold
                                             uppercase">
                                    {{ auth()->user()->role }}
                                </span>

                            @endif

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
                            value="{{ old('no_hp', auth()->user()->no_hp) }}"
                            class="w-full border border-gray-300 rounded-lg
                                   px-4 py-3
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-gray-400"
                        >

                    </div>


                    <!-- ALAMAT -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat
                        </label>

                        <textarea
                            name="alamat"
                            rows="3"
                            class="w-full border border-gray-300 rounded-lg
                                   px-4 py-3
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-gray-400"
                        >{{ old('alamat', auth()->user()->alamat) }}</textarea>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection
