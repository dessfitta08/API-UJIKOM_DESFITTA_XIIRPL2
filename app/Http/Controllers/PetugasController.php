<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Alat;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PEMINJAMAN
    |--------------------------------------------------------------------------
    */

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
            $query->whereHas('user', function ($q) use ($search) {
                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                );
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view(
            'petugas.peminjaman.index',
            compact('peminjamans', 'search')
        );
    }

    public function setujuiPeminjaman($id)
    {
        DB::beginTransaction();

        try {
            $peminjaman = Peminjaman::with('detailPinjams')
                ->findOrFail($id);

            foreach ($peminjaman->detailPinjams as $detail) {
                $alat = Alat::findOrFail($detail->alat_id);

                if ($alat->stok < $detail->jumlah) {
                    throw new \Exception(
                        'Stok alat ' .
                        $alat->nama_alat .
                        ' tidak mencukupi.'
                    );
                }

                $alat->stok -= $detail->jumlah;
                $alat->save();
            }

            $peminjaman->update([
                'status' => 'dipinjam'
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Peminjaman berhasil disetujui.'
                );

        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Peminjaman gagal disetujui.'
                );
        }
    }

    public function tolakPeminjaman($id)
    {
        DB::beginTransaction();

        try {
            $peminjaman = Peminjaman::findOrFail($id);

            $peminjaman->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Peminjaman berhasil ditolak.'
                );

        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Peminjaman gagal ditolak.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PENGEMBALIAN
    |--------------------------------------------------------------------------
    */

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
            $query->whereHas('user', function ($q) use ($search) {
                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                );
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view(
            'petugas.pengembalian.index',
            compact('peminjamans', 'search')
        );
    }

    public function prosesPengembalian(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $peminjaman = Peminjaman::with('detailPinjams')
                ->findOrFail($id);

            $peminjaman->update([
                'status' => 'selesai'
            ]);

            foreach ($peminjaman->detailPinjams as $detail) {
                $alat = Alat::findOrFail($detail->alat_id);

                $alat->stok += $detail->jumlah;
                $alat->save();
            }

            Pengembalian::updateOrCreate(
                [
                    'peminjaman_id' => $peminjaman->id
                ],
                [
                    'tgl_kembali' => now()->toDateString(),
                    'kondisi_kembali' => $request->input(
                        'kondisi_kembali',
                        'Baik'
                    ),
                    'denda' => $request->input(
                        'denda',
                        0
                    ),
                    'petugas_id' => auth()->id(),
                ]
            );

            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Pengembalian berhasil diproses dan stok alat telah dikembalikan.'
                );

        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat memproses pengembalian.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LAPORAN
    |--------------------------------------------------------------------------
    */

    public function laporan(Request $request)
    {
        $query = Peminjaman::with([
            'user',
            'detailPinjams.alat',
            'pengembalian'
        ])
        ->where('status', 'selesai');

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate(
                'tgl_pinjam',
                '>=',
                $request->tanggal_mulai
            );
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate(
                'tgl_pinjam',
                '<=',
                $request->tanggal_selesai
            );
        }

        $laporans = $query
            ->latest()
            ->get();

        return view(
            'petugas.laporan.index',
            compact('laporans')
        );
    }

    public function cetakLaporan(Request $request)
    {
        $query = Peminjaman::with([
            'user',
            'detailPinjams.alat',
            'pengembalian'
        ])
        ->where('status', 'selesai');

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate(
                'tgl_pinjam',
                '>=',
                $request->tanggal_mulai
            );
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate(
                'tgl_pinjam',
                '<=',
                $request->tanggal_selesai
            );
        }

        $laporans = $query
            ->latest()
            ->get();

        return view(
            'petugas.laporan.cetak',
            compact('laporans')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILE PETUGAS
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        $user = auth()->user();

        return view(
            'petugas.profile',
            compact('user')
        );
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $user->update($validated);

        return redirect()
            ->back()
            ->with(
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

        $file = $request->file('foto_profile');

        $filename =
            time() . '_' .
            $file->getClientOriginalName();

        $file->move(
            public_path('storage/profil'),
            $filename
        );

        $user->update([
            'foto_profile' =>
                'profil/' . $filename
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Foto profil berhasil diperbarui.'
            );
    }
}