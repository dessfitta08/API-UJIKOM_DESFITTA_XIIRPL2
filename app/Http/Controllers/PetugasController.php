<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    // =========================================================
    // PEMINJAMAN
    // =========================================================

    // Menampilkan daftar pengajuan peminjaman dari siswa/peminjam
    public function indexPeminjaman(Request $request)
    {
        $search = $request->input('search');

        $peminjamans = Peminjaman::with([
                'user',
                'detailPinjams.alat'
            ])
            ->whereIn('status', [
                'Diajukan',
                'diajukan',
                'pending',
                'Pending'
            ])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view(
            'petugas.peminjaman.index',
            compact('peminjamans', 'search')
        );
    }


    // Menyetujui Peminjaman
    public function setujuiPeminjaman($id)
    {
        DB::beginTransaction();

        try {

            $peminjaman = Peminjaman::with('detailPinjams')
                ->findOrFail($id);

            $peminjaman->update([
                'status' => 'dipinjam'
            ]);

            // Kurangi stok alat secara otomatis
            foreach ($peminjaman->detailPinjams as $detail) {

                $alat = Alat::findOrFail($detail->alat_id);

                $alat->stok -= $detail->jumlah;

                $alat->save();
            }

            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Peminjaman disetujui dan stok alat dikurangi.'
                );

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan: ' . $e->getMessage()
                );
        }
    }


    // Menolak Peminjaman
    public function tolakPeminjaman($id)
    {
        try {

            $peminjaman = Peminjaman::findOrFail($id);

            // Pastikan status masih diajukan / pending
            if (
                in_array(
                    strtolower($peminjaman->status),
                    ['diajukan', 'pending']
                )
            ) {

                $peminjaman->delete();

                return redirect()
                    ->back()
                    ->with(
                        'success',
                        'Pengajuan peminjaman berhasil ditolak.'
                    );
            }

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Status peminjaman sudah berubah.'
                );

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan: ' . $e->getMessage()
                );
        }
    }


    // =========================================================
    // PENGEMBALIAN
    // =========================================================

    // Menampilkan daftar pemantauan pengembalian alat
    public function indexPengembalian(Request $request)
    {
        $search = $request->input('search');

        $peminjamans = Peminjaman::with([
                'user',
                'detailPinjams.alat',
                'pengembalian'
            ])
            ->whereIn('status', [
                'dipinjam',
                'Dipinjam',
                'selesai',
                'Selesai',
                'telat',
                'Telat'
            ])
            ->when($search, function ($query, $search) {

                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );
                });

            })
            ->latest()
            ->get();

        return view(
            'petugas.pengembalian.index',
            compact('peminjamans', 'search')
        );
    }


    // Memproses pengembalian alat oleh petugas
    public function prosesPengembalian(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $peminjaman = Peminjaman::with('detailPinjams')
                ->findOrFail($id);

            // Ubah status peminjaman menjadi selesai
            $peminjaman->update([
                'status' => 'selesai'
            ]);

            // Kembalikan stok alat
            foreach ($peminjaman->detailPinjams as $detail) {

                $alat = Alat::findOrFail($detail->alat_id);

                $alat->stok += $detail->jumlah;

                $alat->save();
            }

            // Catat ke tabel pengembalian
            Pengembalian::updateOrCreate(
                [
                    'peminjaman_id' => $peminjaman->id
                ],
                [
                    'user_id' => $peminjaman->user_id,
                    'tanggal_dikembalikan' => now()->toDateString(),
                    'kondisi_kembali' => $request->input(
                        'kondisi_kembali',
                        'Baik'
                    ),
                    'denda' => $request->input('denda', 0),
                    'status' => 'selesai'
                ]
            );

            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Pengembalian berhasil diproses dan stok alat telah dikembalikan.'
                );

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan: ' . $e->getMessage()
                );
        }
    }


    // =========================================================
    // LAPORAN
    // =========================================================

    // Menampilkan halaman laporan petugas
    public function laporan(Request $request)
    {
        $status = $request->input('status');

        $dari_tanggal = $request->input('dari_tanggal');

        $sampai_tanggal = $request->input('sampai_tanggal');

        $laporans = Peminjaman::with([
                'user',
                'detailPinjams.alat',
                'pengembalian'
            ])
            ->when($status, function ($query, $status) {

                return $query->where('status', $status);

            })
            ->when(
                $dari_tanggal && $sampai_tanggal,
                function ($query) use (
                    $dari_tanggal,
                    $sampai_tanggal
                ) {

                    return $query->whereBetween(
                        'tgl_pinjam',
                        [
                            $dari_tanggal,
                            $sampai_tanggal
                        ]
                    );
                }
            )
            ->latest()
            ->get();

        return view(
            'petugas.laporan.index',
            compact(
                'laporans',
                'status',
                'dari_tanggal',
                'sampai_tanggal'
            )
        );
    }


    // Menampilkan halaman khusus cetak laporan
    public function cetakLaporan(Request $request)
    {
        $status = $request->input('status');

        $dari_tanggal = $request->input('dari_tanggal');

        $sampai_tanggal = $request->input('sampai_tanggal');

        $laporans = Peminjaman::with([
                'user',
                'detailPinjams.alat',
                'pengembalian'
            ])
            ->when($status, function ($query, $status) {

                return $query->where('status', $status);

            })
            ->when(
                $dari_tanggal && $sampai_tanggal,
                function ($query) use (
                    $dari_tanggal,
                    $sampai_tanggal
                ) {

                    return $query->whereBetween(
                        'tgl_pinjam',
                        [
                            $dari_tanggal,
                            $sampai_tanggal
                        ]
                    );
                }
            )
            ->latest()
            ->get();

        return view(
            'petugas.laporan.cetak',
            compact(
                'laporans',
                'status',
                'dari_tanggal',
                'sampai_tanggal'
            )
        );
    }


    // =========================================================
    // PROFIL PETUGAS
    // =========================================================

    // Menampilkan halaman profil petugas
    public function profile()
    {
        $user = auth()->user();

        return view(
            'petugas.profile',
            compact('user')
        );
    }


    // =========================================================
    // UPDATE PROFIL + FOTO
    // =========================================================

    // Memperbarui data profil sekaligus foto profil
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id
            ],

            'no_hp' => 'nullable|string|max:20',

            'alamat' => 'nullable|string',

            'foto_profile' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA DASAR
        |--------------------------------------------------------------------------
        */

        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->alamat = $request->alamat;


        /*
        |--------------------------------------------------------------------------
        | UPDATE FOTO PROFIL
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('foto_profile')) {

            /*
            |--------------------------------------------------------------------------
            | HAPUS FOTO LAMA
            |--------------------------------------------------------------------------
            */

            if ($user->foto_profile) {

                $oldPhoto = public_path(
                    $user->foto_profile
                );

                if (
                    file_exists($oldPhoto) &&
                    is_file($oldPhoto)
                ) {
                    unlink($oldPhoto);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | PASTIKAN FOLDER TERSEDIA
            |--------------------------------------------------------------------------
            */

            $folder = public_path('storage/profil');

            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }


            /*
            |--------------------------------------------------------------------------
            | SIMPAN FOTO BARU
            |--------------------------------------------------------------------------
            */

            $file = $request->file('foto_profile');

            $filename =
                time() .
                '_' .
                uniqid() .
                '_' .
                $file->getClientOriginalName();


            $file->move(
                $folder,
                $filename
            );


            /*
            |--------------------------------------------------------------------------
            | SIMPAN PATH KE DATABASE
            |--------------------------------------------------------------------------
            |
            | Nilai database:
            | storage/profil/nama-file.jpg
            |
            | Nanti Blade cukup:
            | asset($user->foto_profile)
            |
            */

            $user->foto_profile =
                'storage/profil/' . $filename;
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN USER
        |--------------------------------------------------------------------------
        */

        $user->save();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('petugas.profile')
            ->with(
                'success',
                'Profil berhasil diperbarui.'
            );
    }


    // =========================================================
    // UPDATE FOTO TERPISAH
    // =========================================================

    // Tetap dipertahankan untuk route lama
    public function updateFoto(Request $request)
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'foto_profile' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | CEK FILE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('foto_profile')) {

            /*
            |--------------------------------------------------------------------------
            | HAPUS FOTO LAMA
            |--------------------------------------------------------------------------
            */

            if ($user->foto_profile) {

                $oldPhoto = public_path(
                    $user->foto_profile
                );

                if (
                    file_exists($oldPhoto) &&
                    is_file($oldPhoto)
                ) {
                    unlink($oldPhoto);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | PASTIKAN FOLDER TERSEDIA
            |--------------------------------------------------------------------------
            */

            $folder = public_path('storage/profil');

            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }


            /*
            |--------------------------------------------------------------------------
            | SIMPAN FOTO BARU
            |--------------------------------------------------------------------------
            */

            $file = $request->file('foto_profile');

            $filename =
                time() .
                '_' .
                uniqid() .
                '_' .
                $file->getClientOriginalName();


            $file->move(
                $folder,
                $filename
            );


            /*
            |--------------------------------------------------------------------------
            | SIMPAN PATH
            |--------------------------------------------------------------------------
            */

            $user->foto_profile =
                'storage/profil/' . $filename;

            $user->save();
        }


        return redirect()
            ->route('petugas.profile')
            ->with(
                'success',
                'Foto profil berhasil diperbarui.'
            );
    }
}
