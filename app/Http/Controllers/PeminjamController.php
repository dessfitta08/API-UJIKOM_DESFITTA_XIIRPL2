<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\DetailPinjam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PeminjamController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | KATALOG ALAT
    |--------------------------------------------------------------------------
    */

    // Melihat daftar/katalog alat yang tersedia
    public function katalogAlat()
    {
        $alats = Alat::with('kategori')
            ->where('stok', '>', 0)
            ->get();

        return view('peminjam.katalog', compact('alats'));
    }


    /*
    |--------------------------------------------------------------------------
    | PROFIL PEMINJAM
    |--------------------------------------------------------------------------
    */

    // Menampilkan profil peminjam
    public function profile()
    {
        $user = auth()->user();

        return view('peminjam.profile', compact('user'));
    }


    // Memperbarui data profil peminjam
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'no_hp.max' => 'Nomor HP maksimal 15 karakter.',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()
            ->route('peminjam.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }


    // Mengubah foto profil peminjam
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto_profile' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'foto_profile.required' => 'Foto profil wajib dipilih.',
            'foto_profile.image' => 'File yang dipilih harus berupa gambar.',
            'foto_profile.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'foto_profile.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $user = auth()->user();

        // Hapus foto lama jika ada
        if ($user->foto_profile) {
            $oldPath = $user->foto_profile;

            // Mengubah "storage/profil/xxx.jpg"
            // menjadi "profil/xxx.jpg"
            if (str_starts_with($oldPath, 'storage/')) {
                $oldPath = substr($oldPath, 8);
            }

            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        // Simpan foto baru ke storage/app/public/profil
        $path = $request->file('foto_profile')
            ->store('profil', 'public');

        $user->update([
            'foto_profile' => 'storage/' . $path,
        ]);

        return redirect()
            ->route('peminjam.profile')
            ->with('success', 'Foto profil berhasil diperbarui.');
    }


    /*
    |--------------------------------------------------------------------------
    | AJUKAN PEMINJAMAN
    |--------------------------------------------------------------------------
    */

    // Mengajukan peminjaman
    public function ajukanPeminjaman(Request $request)
    {
        $request->validate([
            'tgl_kembali_plan' => 'required|date|after:today',
            'alat_id' => 'required|array',
            'jumlah' => 'required|array',
        ]);

        DB::beginTransaction();

        try {

            // Buat header peminjaman
            $peminjaman = Peminjaman::create([
                'user_id' => auth()->id(),
                'tgl_pinjam' => now(),
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
                'status' => 'Diajukan',
            ]);

            // Masukkan daftar alat ke detail_pinjam
            foreach ($request->alat_id as $index => $alatId) {
                DetailPinjam::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id' => $alatId,
                    'jumlah' => $request->jumlah[$index],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('peminjam.riwayat')
                ->with('success', 'Pengajuan peminjaman berhasil dikirim.');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Gagal mengajukan peminjaman: ' . $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | RIWAYAT PEMINJAMAN
    |--------------------------------------------------------------------------
    */

    // Melihat riwayat peminjaman user yang sedang login
    public function riwayatPeminjaman()
    {
        $peminjamans = Peminjaman::with('detailPinjams.alat')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('peminjam.riwayat', compact('peminjamans'));
    }
}