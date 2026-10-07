<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\User;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\DetailPinjam;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard()
{
    // Total data untuk kartu dashboard
    $totalUser = User::count();
    $totalKategori = Kategori::count();
    $totalAlat = Alat::count();
    $totalPeminjaman = Peminjaman::count();
    $totalPengembalian = Pengembalian::count();

    // Log aktivitas terbaru
    $logs = LogAktivitas::with('user')
        ->latest()
        ->take(10)
        ->get();

    return view('admin.dashboard', compact(
        'totalUser',
        'totalKategori',
        'totalAlat',
        'totalPeminjaman',
        'totalPengembalian',
        'logs'
    ));
}


    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        return view('admin.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:users,email,' . $user->id,
            'no_hp'  => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        LogAktivitas::create([
            'user_id'   => auth()->id(),
            'aktivitas' => 'Memperbarui profil',
        ]);

        return back()->with(
            'success',
            'Profil berhasil diperbarui.'
        );
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto_profile' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('foto_profile')) {

            $folder = public_path('storage/profil');

            if (!File::exists($folder)) {
                File::makeDirectory(
                    $folder,
                    0755,
                    true
                );
            }

            // Hapus foto lama
            if ($user->foto_profile) {

                $fotoLama = public_path(
                    $user->foto_profile
                );

                if (File::exists($fotoLama)) {
                    File::delete($fotoLama);
                }
            }

            $file = $request->file('foto_profile');

            $filename =
                time() . '_' .
                $file->getClientOriginalName();

            $file->move(
                $folder,
                $filename
            );

            $user->update([
                'foto_profile' =>
                    'storage/profil/' . $filename,
            ]);
        }

        LogAktivitas::create([
            'user_id'   => auth()->id(),
            'aktivitas' => 'Memperbarui foto profil',
        ]);

        return back()->with(
            'success',
            'Foto profil berhasil diperbarui.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

    public function indexUser(Request $request)
    {
        $search = $request->input('search');

        $users = User::when(
            $search,
            function ($query, $search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'email',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'role',
                        'like',
                        "%{$search}%"
                    );
                });
            }
        )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.user.index',
            compact('users', 'search')
        );
    }

    public function createUser()
    {
        return view('admin.user.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' =>
                'required|string|max:255',

            'email' =>
                'required|email|unique:users,email',

            'password' =>
                'required|string|min:6|confirmed',

            'role' =>
                'required|in:admin,petugas,peminjam',

            'no_hp' =>
                'nullable|string|max:20',

            'alamat' =>
                'nullable|string|max:500',

            'foto_profile' =>
                'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoProfile = null;

        if ($request->hasFile('foto_profile')) {

            $folder = public_path('storage/profil');

            if (!File::exists($folder)) {
                File::makeDirectory(
                    $folder,
                    0755,
                    true
                );
            }

            $file = $request->file('foto_profile');

            $filename =
                time() . '_' .
                $file->getClientOriginalName();

            $file->move(
                $folder,
                $filename
            );

            $fotoProfile =
                'storage/profil/' . $filename;
        }

        User::create([
            'name' =>
                $validated['name'],

            'email' =>
                $validated['email'],

            'password' =>
                Hash::make(
                    $validated['password']
                ),

            'role' =>
                $validated['role'],

            'no_hp' =>
                $validated['no_hp'] ?? null,

            'alamat' =>
                $validated['alamat'] ?? null,

            'foto_profile' =>
                $fotoProfile,
        ]);

        LogAktivitas::create([
            'user_id' =>
                auth()->id(),

            'aktivitas' =>
                'Menambahkan user: ' .
                $validated['name'],
        ]);

        return redirect()
            ->route('admin.user.index')
            ->with(
                'success',
                'User berhasil ditambahkan.'
            );
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);

        return view(
            'admin.user.edit',
            compact('user')
        );
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' =>
                'required|string|max:255',

            'email' =>
                'required|email|unique:users,email,' .
                $user->id,

            'role' =>
                'required|in:admin,petugas,peminjam',

            'no_hp' =>
                'nullable|string|max:20',

            'alamat' =>
                'nullable|string|max:500',

            'password' =>
                'nullable|string|min:6',

            'foto_profile' =>
                'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->update([
            'name' =>
                $validated['name'],

            'email' =>
                $validated['email'],

            'role' =>
                $validated['role'],

            'no_hp' =>
                $validated['no_hp'] ?? null,

            'alamat' =>
                $validated['alamat'] ?? null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | PASSWORD BARU
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['password'])) {

            $user->update([
                'password' =>
                    Hash::make(
                        $validated['password']
                    ),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | FOTO PROFILE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('foto_profile')) {

            $folder =
                public_path(
                    'storage/profil'
                );

            if (!File::exists($folder)) {

                File::makeDirectory(
                    $folder,
                    0755,
                    true
                );
            }

            // Hapus foto lama
            if ($user->foto_profile) {

                $fotoLama =
                    public_path(
                        $user->foto_profile
                    );

                if (File::exists($fotoLama)) {

                    File::delete(
                        $fotoLama
                    );
                }
            }

            $file =
                $request->file(
                    'foto_profile'
                );

            $filename =
                time() . '_' .
                $file->getClientOriginalName();

            $file->move(
                $folder,
                $filename
            );

            $user->update([
                'foto_profile' =>
                    'storage/profil/' .
                    $filename,
            ]);
        }

        LogAktivitas::create([
            'user_id' =>
                auth()->id(),

            'aktivitas' =>
                'Memperbarui user: ' .
                $user->name,
        ]);

        return redirect()
            ->route('admin.user.index')
            ->with(
                'success',
                'User berhasil diperbarui.'
            );
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        $nama = $user->name;

        // Hapus foto profile
        if ($user->foto_profile) {

            $foto =
                public_path(
                    $user->foto_profile
                );

            if (File::exists($foto)) {
                File::delete($foto);
            }
        }

        $user->delete();

        LogAktivitas::create([
            'user_id' =>
                auth()->id(),

            'aktivitas' =>
                'Menghapus user: ' .
                $nama,
        ]);

        return redirect()
            ->route('admin.user.index')
            ->with(
                'success',
                'User berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | KATEGORI
    |--------------------------------------------------------------------------
    */

    public function indexKategori(Request $request)
    {
        $search = $request->input('search');

        $kategoris = Kategori::when(
            $search,
            function ($query, $search) {

                $query->where(
                    'nama_kategori',
                    'like',
                    "%{$search}%"
                );
            }
        )
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'admin.kategori.index',
            compact(
                'kategoris',
                'search'
            )
        );
    }

    public function createKategori()
    {
        return view(
            'admin.kategori.create'
        );
    }

    public function storeKategori(
        Request $request
    ) {
        $validated =
            $request->validate([
                'nama_kategori' =>
                    'required|string|max:255|unique:kategori,nama_kategori',
            ]);

        Kategori::create(
            $validated
        );

        LogAktivitas::create([
            'user_id' =>
                auth()->id(),

            'aktivitas' =>
                'Menambahkan kategori: ' .
                $validated['nama_kategori'],
        ]);

        return redirect()
            ->route(
                'admin.kategori.index'
            )
            ->with(
                'success',
                'Kategori berhasil ditambahkan.'
            );
    }

    public function editKategori($id)
    {
        $kategori =
            Kategori::findOrFail($id);

        return view(
            'admin.kategori.edit',
            compact('kategori')
        );
    }

    public function updateKategori(
        Request $request,
        $id
    ) {
        $kategori =
            Kategori::findOrFail($id);

        $validated =
            $request->validate([
                'nama_kategori' =>
                    'required|string|max:255|unique:kategori,nama_kategori,' .
                    $id,
            ]);

        $kategori->update(
            $validated
        );

        LogAktivitas::create([
            'user_id' =>
                auth()->id(),

            'aktivitas' =>
                'Memperbarui kategori: ' .
                $validated['nama_kategori'],
        ]);

        return redirect()
            ->route(
                'admin.kategori.index'
            )
            ->with(
                'success',
                'Kategori berhasil diperbarui.'
            );
    }

    public function destroyKategori($id)
    {
        $kategori =
            Kategori::findOrFail($id);

        // Cek apakah kategori masih digunakan alat
        $jumlahAlat =
            Alat::where(
                'kategori_id',
                $id
            )->count();

        if ($jumlahAlat > 0) {

            return back()->with(
                'error',
                'Kategori tidak dapat dihapus karena masih digunakan oleh alat.'
            );
        }

        $namaKategori =
            $kategori->nama_kategori;

        $kategori->delete();

        LogAktivitas::create([
            'user_id' =>
                auth()->id(),

            'aktivitas' =>
                'Menghapus kategori: ' .
                $namaKategori,
        ]);

        return redirect()
            ->route(
                'admin.kategori.index'
            )
            ->with(
                'success',
                'Kategori berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ALAT
    |--------------------------------------------------------------------------
    */

    public function indexAlat(Request $request)
    {
        $search =
            $request->input('search');

        $alats = Alat::with('kategori')
            ->when(
                $search,
                function ($query, $search) {

                    $query->where(
                        function ($q) use ($search) {

                            $q->where(
                                'nama_alat',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'status_kondisi',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhereHas(
                                'kategori',
                                function ($kategori) use ($search) {

                                    $kategori->where(
                                        'nama_kategori',
                                        'like',
                                        "%{$search}%"
                                    );
                                }
                            );
                        }
                    );
                }
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.alat.index',
            compact(
                'alats',
                'search'
            )
        );
    }

    public function createAlat()
    {
        $kategoris =
            Kategori::all();

        return view(
            'admin.alat.create',
            compact('kategoris')
        );
    }

    public function storeAlat(
        Request $request
    ) {
        $validated =
            $request->validate([
                'nama_alat' =>
                    'required|string|max:255',

                'kategori_id' =>
                    'required|exists:kategori,id',

                'stok' =>
                    'required|integer|min:0',

                'status_kondisi' =>
                    'required|string|max:255',

                'deskripsi' =>
                    'nullable|string',

                'gambar' =>
                    'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

        $gambar = null;

        /*
        |--------------------------------------------------------------------------
        | UPLOAD GAMBAR ALAT
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('gambar')) {

            $folder =
                public_path(
                    'storage/alat'
                );

            if (!File::exists($folder)) {

                File::makeDirectory(
                    $folder,
                    0755,
                    true
                );
            }

            $file =
                $request->file('gambar');

            $filename =
                time() . '_' .
                $file->getClientOriginalName();

            $file->move(
                $folder,
                $filename
            );

            // Simpan path relatif di database
            $gambar =
                'alat/' . $filename;
        }

        Alat::create([
            'nama_alat' =>
                $validated['nama_alat'],

            'kategori_id' =>
                $validated['kategori_id'],

            'stok' =>
                $validated['stok'],

            'status_kondisi' =>
                $validated['status_kondisi'],

            'deskripsi' =>
                $validated['deskripsi'] ?? null,

            'gambar' =>
                $gambar,
        ]);

        LogAktivitas::create([
            'user_id' =>
                auth()->id(),

            'aktivitas' =>
                'Menambahkan alat: ' .
                $validated['nama_alat'],
        ]);

        return redirect()
            ->route(
                'admin.alat.index'
            )
            ->with(
                'success',
                'Alat berhasil ditambahkan.'
            );
    }

    public function editAlat($id)
    {
        $alat =
            Alat::findOrFail($id);

        $kategoris =
            Kategori::all();

        return view(
            'admin.alat.edit',
            compact(
                'alat',
                'kategoris'
            )
        );
    }

    public function updateAlat(
        Request $request,
        $id
    ) {
        $alat =
            Alat::findOrFail($id);

        $validated =
            $request->validate([
                'nama_alat' =>
                    'required|string|max:255',

                'kategori_id' =>
                    'required|exists:kategori,id',

                'stok' =>
                    'required|integer|min:0',

                'status_kondisi' =>
                    'required|string|max:255',

                'deskripsi' =>
                    'nullable|string',

                'gambar' =>
                    'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

        $data = [
            'nama_alat' =>
                $validated['nama_alat'],

            'kategori_id' =>
                $validated['kategori_id'],

            'stok' =>
                $validated['stok'],

            'status_kondisi' =>
                $validated['status_kondisi'],

            'deskripsi' =>
                $validated['deskripsi'] ?? null,
        ];

        /*
        |--------------------------------------------------------------------------
        | GAMBAR BARU
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('gambar')) {

            // Hapus gambar lama
            if ($alat->gambar) {

                $gambarLama =
                    public_path(
                        'storage/' .
                        $alat->gambar
                    );

                if (File::exists($gambarLama)) {

                    File::delete(
                        $gambarLama
                    );
                }
            }

            $folder =
                public_path(
                    'storage/alat'
                );

            if (!File::exists($folder)) {

                File::makeDirectory(
                    $folder,
                    0755,
                    true
                );
            }

            $file =
                $request->file('gambar');

            $filename =
                time() . '_' .
                $file->getClientOriginalName();

            $file->move(
                $folder,
                $filename
            );

            $data['gambar'] =
                'alat/' . $filename;
        }

        $alat->update($data);

        LogAktivitas::create([
            'user_id' =>
                auth()->id(),

            'aktivitas' =>
                'Memperbarui alat: ' .
                $alat->nama_alat,
        ]);

        return redirect()
            ->route(
                'admin.alat.index'
            )
            ->with(
                'success',
                'Alat berhasil diperbarui.'
            );
    }

    public function destroyAlat($id)
    {
        $alat =
            Alat::findOrFail($id);

        $namaAlat =
            $alat->nama_alat;

        // Hapus file gambar
        if ($alat->gambar) {

            $gambar =
                public_path(
                    'storage/' .
                    $alat->gambar
                );

            if (File::exists($gambar)) {
                File::delete($gambar);
            }
        }

        $alat->delete();

        LogAktivitas::create([
            'user_id' =>
                auth()->id(),

            'aktivitas' =>
                'Menghapus alat: ' .
                $namaAlat,
        ]);

        return redirect()
            ->route(
                'admin.alat.index'
            )
            ->with(
                'success',
                'Alat berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PEMINJAMAN
    |--------------------------------------------------------------------------
    */

    public function indexPeminjaman(
        Request $request
    ) {
        $search =
            $request->input('search');

        $peminjamans =
            Peminjaman::with([
                'user',
                'detailpinjam.alat'
            ])
            ->when(
                $search,
                function ($query, $search) {

                    $query->whereHas(
                        'user',
                        function ($user) use ($search) {

                            $user->where(
                                'name',
                                'like',
                                "%{$search}%"
                            );
                        }
                    );
                }
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.peminjaman.index',
            compact(
                'peminjamans',
                'search'
            )
        );
    }

    public function createPeminjaman()
    {
        $users =
            User::where(
                'role',
                'peminjam'
            )->get();

        $alats =
            Alat::all();

        return view(
            'admin.peminjaman.create',
            compact(
                'users',
                'alats'
            )
        );
    }

    public function storePeminjaman(
        Request $request
    ) {
        $validated =
            $request->validate([
                'user_id' =>
                    'required|exists:users,id',

                'tgl_pinjam' =>
                    'required|date',

                'tgl_kembali_plan' =>
                    'required|date|after_or_equal:tgl_pinjam',

                'alat_id' =>
                    'required|array',

                'alat_id.*' =>
                    'required|exists:alat,id',

                'jumlah' =>
                    'required|array',

                'jumlah.*' =>
                    'required|integer|min:1',
            ]);

        DB::beginTransaction();

        try {

            $peminjaman =
                Peminjaman::create([
                    'user_id' =>
                        $validated['user_id'],

                    'tgl_pinjam' =>
                        $validated['tgl_pinjam'],

                    'tgl_kembali_plan' =>
                        $validated['tgl_kembali_plan'],

                    'status' =>
                        'diajukan',
                ]);

            foreach (
                $validated['alat_id']
                as $index => $alatId
            ) {

                $jumlah =
                    $validated['jumlah'][$index];

                $alat =
                    Alat::findOrFail(
                        $alatId
                    );

                // Cek stok
                if ($alat->stok < $jumlah) {

                    throw new \Exception(
                        'Jumlah alat ' .
                        $alat->nama_alat .
                        ' tidak mencukupi.'
                    );
                }

                DetailPinjam::create([
                    'peminjaman_id' =>
                        $peminjaman->id,

                    'alat_id' =>
                        $alatId,

                    'jumlah' =>
                        $jumlah,
                ]);
            }

            LogAktivitas::create([
                'user_id' =>
                    auth()->id(),

                'aktivitas' =>
                    'Menambahkan peminjaman',
            ]);

            DB::commit();

            return redirect()
                ->route(
                    'admin.peminjaman.index'
                )
                ->with(
                    'success',
                    'Peminjaman berhasil dibuat.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Peminjaman gagal dibuat.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS PEMINJAMAN
    |--------------------------------------------------------------------------
    */

    public function updateStatusPeminjaman(
        Request $request,
        $id
    ) {
        DB::beginTransaction();

        try {

            $peminjaman = Peminjaman::with([
                'detailPinjams.alat',
                'pengembalian'
            ])->findOrFail($id);

            $validated = $request->validate([
                'status' =>
                    'required|in:diajukan,dipinjam,ditolak,telat,selesai,dikembalikan',
            ]);

            $statusBaru =
                $validated['status'];

            $statusLama =
                strtolower(
                    $peminjaman->status
                );

            /*
            |--------------------------------------------------------------------------
            | JIKA STATUS MENJADI SELESAI
            |--------------------------------------------------------------------------
            */

            if ($statusBaru === 'selesai') {

                /*
                |--------------------------------------------------------------------------
                | Cek apakah pengembalian sudah ada
                |--------------------------------------------------------------------------
                */

                $pengembalianSudahAda =
                    Pengembalian::where(
                        'peminjaman_id',
                        $peminjaman->id
                    )->exists();

                /*
                |--------------------------------------------------------------------------
                | Kembalikan stok hanya jika sebelumnya
                | status dipinjam atau telat.
                |--------------------------------------------------------------------------
                */

                if (
                    !$pengembalianSudahAda &&
                    in_array(
                        $statusLama,
                        [
                            'dipinjam',
                            'telat'
                        ],
                        true
                    )
                ) {

                    foreach (
                        $peminjaman->detailPinjams
                        as $detail
                    ) {

                        $alat =
                            Alat::findOrFail(
                                $detail->alat_id
                            );

                        $alat->stok +=
                            $detail->jumlah;

                        $alat->save();
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Buat data pengembalian
                |--------------------------------------------------------------------------
                */

                if (!$pengembalianSudahAda) {

                    Pengembalian::create([
                        'peminjaman_id' =>
                            $peminjaman->id,

                        'tgl_kembali' =>
                            now()->toDateString(),

                        'kondisi_kembali' =>
                            'Baik',

                        'denda' =>
                            0,

                        'petugas_id' =>
                            auth()->id(),
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Update status peminjaman
            |--------------------------------------------------------------------------
            */

            $peminjaman->update([
                'status' =>
                    $statusBaru,
            ]);

            /*
            |--------------------------------------------------------------------------
            | LOG AKTIVITAS
            |--------------------------------------------------------------------------
            */

            LogAktivitas::create([
                'user_id' =>
                    auth()->id(),

                'aktivitas' =>
                    'Memperbarui status peminjaman menjadi: ' .
                    $statusBaru,
            ]);

            DB::commit();

            return back()->with(
                'success',
                'Status peminjaman berhasil diperbarui.'
            );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()->with(
                'error',
                'Status peminjaman gagal diperbarui.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS PEMINJAMAN
    |--------------------------------------------------------------------------
    */

    public function destroyPeminjaman($id)
    {
        DB::beginTransaction();

        try {

            $peminjaman =
                Peminjaman::with('user')
                ->findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | PEMINJAMAN HANYA BOLEH DIHAPUS JIKA SUDAH SELESAI
            |--------------------------------------------------------------------------
            */

            if ($peminjaman->status !== 'selesai') {

                DB::rollBack();

                return back()->with(
                    'error',
                    'Peminjaman belum selesai dan belum dapat dihapus.'
                );
            }

            $namaPeminjam =
                $peminjaman->user->name ??
                'Tidak diketahui';

            // Hapus detail peminjaman terlebih dahulu
            DetailPinjam::where(
                'peminjaman_id',
                $peminjaman->id
            )->delete();

            // Hapus data peminjaman
            $peminjaman->delete();

            LogAktivitas::create([
                'user_id' =>
                    auth()->id(),

                'aktivitas' =>
                    'Menghapus peminjaman dari: ' .
                    $namaPeminjam,
            ]);

            DB::commit();

            return redirect()
                ->route(
                    'admin.peminjaman.index'
                )
                ->with(
                    'success',
                    'Peminjaman berhasil dihapus.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return redirect()
                ->route(
                    'admin.peminjaman.index'
                )
                ->with(
                    'error',
                    'Peminjaman gagal dihapus.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LOG AKTIVITAS
    |--------------------------------------------------------------------------
    */

    public function indexLogAktivitas(
        Request $request
    ) {
        $search =
            $request->input('search');

        $logs =
            LogAktivitas::with('user')
            ->whereDate(
                'created_at',
                today()
            )
            ->when(
                $search,
                function ($query, $search) {

                    $query->where(
                        function ($q) use ($search) {

                            $q->where(
                                'aktivitas',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhereHas(
                                'user',
                                function ($user) use ($search) {

                                    $user->where(
                                        'name',
                                        'like',
                                        "%{$search}%"
                                    );
                                }
                            );
                        }
                    );
                }
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.logaktivitas.index',
            compact(
                'logs',
                'search'
            )
        );
    }
}