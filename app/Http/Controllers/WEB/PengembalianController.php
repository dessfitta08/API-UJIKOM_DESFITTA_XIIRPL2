<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PengembalianController extends Controller
{
    /**
     * ============================================================
     * MENAMPILKAN DATA PENGEMBALIAN
     * ============================================================
     */
    public function index(Request $request)
    {
        $search = $request->search;

        /*
        |--------------------------------------------------------------------------
        | PEMINJAMAN AKTIF
        |--------------------------------------------------------------------------
        | Hanya menampilkan peminjaman yang masih dipinjam atau terlambat.
        | Status selesai tidak akan muncul di bagian ini.
        |--------------------------------------------------------------------------
        */

        $peminjamanAktif = Peminjaman::with([
            'user',
            'detailPinjams.alat'
        ])
        ->whereIn('status', ['dipinjam', 'telat'])
        ->latest('id')
        ->get();

        /*
        |--------------------------------------------------------------------------
        | DATA PENGEMBALIAN
        |--------------------------------------------------------------------------
        */

        $pengembalians = Pengembalian::with([
            'peminjaman.user',
            'peminjaman.detailPinjams.alat',
            'petugas'
        ])
        ->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                // Cari berdasarkan kondisi
                $q->where(
                    'kondisi_kembali',
                    'like',
                    "%{$search}%"
                )

                // Cari berdasarkan denda
                ->orWhere(
                    'denda',
                    'like',
                    "%{$search}%"
                )

                // Cari berdasarkan nama peminjam
                ->orWhereHas(
                    'peminjaman.user',
                    function ($user) use ($search) {
                        $user->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    }
                )

                // Cari berdasarkan nama alat
                ->orWhereHas(
                    'peminjaman.detailPinjams.alat',
                    function ($alat) use ($search) {
                        $alat->where(
                            'nama_alat',
                            'like',
                            "%{$search}%"
                        );
                    }
                )

                // Cari berdasarkan nama petugas
                ->orWhereHas(
                    'petugas',
                    function ($petugas) use ($search) {
                        $petugas->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    }
                );

            });

        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view(
            'admin.pengembalian.index',
            compact(
                'pengembalians',
                'peminjamanAktif',
                'search'
            )
        );
    }


    /**
     * ============================================================
     * FORM PROSES PENGEMBALIAN
     * ============================================================
     */
    public function create($peminjaman_id)
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil data peminjaman beserta detail alat
        |--------------------------------------------------------------------------
        */

        $peminjaman = Peminjaman::with([
            'user',
            'detailPinjams.alat'
        ])->findOrFail($peminjaman_id);

        /*
        |--------------------------------------------------------------------------
        | Pengembalian hanya boleh dilakukan jika status:
        | dipinjam atau telat
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                strtolower($peminjaman->status),
                ['dipinjam', 'telat'],
                true
            )
        ) {
            return redirect()
                ->route('admin.pengembalian.index')
                ->with(
                    'error',
                    'Peminjaman ini tidak dapat diproses karena statusnya bukan dipinjam atau telat.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Cegah pengembalian ganda
        |--------------------------------------------------------------------------
        */

        $sudahAda = Pengembalian::where(
            'peminjaman_id',
            $peminjaman->id
        )->exists();

        if ($sudahAda) {
            return redirect()
                ->route('admin.pengembalian.index')
                ->with(
                    'error',
                    'Peminjaman ini sudah memiliki data pengembalian.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Tampilkan halaman proses pengembalian
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.pengembalian.create',
            compact('peminjaman')
        );
    }


    /**
     * ============================================================
     * SIMPAN PENGEMBALIAN
     * ============================================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => [
                'required',
                'exists:peminjaman,id'
            ],

            'kondisi_kembali' => [
                'required',
                'string',
                'max:255'
            ],

            'denda' => [
                'nullable',
                'numeric',
                'min:0'
            ],
        ]);

        try {

            DB::transaction(function () use ($request) {

                /*
                |--------------------------------------------------------------------------
                | Ambil peminjaman dan kunci data
                |--------------------------------------------------------------------------
                */

                $peminjaman = Peminjaman::with(
                    'detailPinjams'
                )
                ->lockForUpdate()
                ->findOrFail(
                    $request->peminjaman_id
                );

                /*
                |--------------------------------------------------------------------------
                | Pastikan status masih dipinjam / telat
                |--------------------------------------------------------------------------
                */

                if (
                    !in_array(
                        strtolower($peminjaman->status),
                        ['dipinjam', 'telat'],
                        true
                    )
                ) {
                    throw new Exception(
                        "Peminjaman tidak dapat diproses karena statusnya '{$peminjaman->status}'."
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Cegah pengembalian ganda
                |--------------------------------------------------------------------------
                */

                $pengembalianSudahAda =
                    Pengembalian::where(
                        'peminjaman_id',
                        $peminjaman->id
                    )->exists();

                if ($pengembalianSudahAda) {
                    throw new Exception(
                        'Peminjaman ini sudah memiliki data pengembalian.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Simpan data pengembalian
                |--------------------------------------------------------------------------
                */

                Pengembalian::create([
                    'peminjaman_id' =>
                        $peminjaman->id,

                    'tgl_kembali' =>
                        now()->toDateString(),

                    'kondisi_kembali' =>
                        $request->kondisi_kembali,

                    'denda' =>
                        $request->denda ?? 0,

                    'petugas_id' =>
                        auth()->id(),
                ]);

                /*
                |--------------------------------------------------------------------------
                | Kembalikan stok alat
                |--------------------------------------------------------------------------
                */

                foreach (
                    $peminjaman->detailPinjams as $detail
                ) {

                    $alat = Alat::lockForUpdate()
                        ->findOrFail(
                            $detail->alat_id
                        );

                    $alat->increment(
                        'stok',
                        $detail->jumlah
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Status peminjaman menjadi selesai
                |--------------------------------------------------------------------------
                */

                $peminjaman->update([
                    'status' => 'selesai'
                ]);
            });

            return redirect()
                ->route(
                    'admin.pengembalian.index'
                )
                ->with(
                    'success',
                    'Pengembalian alat berhasil disimpan.'
                );

        } catch (Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    /**
     * ============================================================
     * FORM EDIT PENGEMBALIAN
     * ============================================================
     */
    public function edit($id)
    {
        $pengembalian = Pengembalian::with([
            'peminjaman.user',
            'peminjaman.detailPinjams.alat',
            'petugas'
        ])->findOrFail($id);

        return view(
            'admin.pengembalian.edit',
            compact('pengembalian')
        );
    }


    /**
     * ============================================================
     * UPDATE PENGEMBALIAN
     * ============================================================
     */
    public function update(
        Request $request,
        $id
    ) {
        $request->validate([
            'kondisi_kembali' => [
                'required',
                'string',
                'max:255'
            ],

            'denda' => [
                'nullable',
                'numeric',
                'min:0'
            ],
        ]);

        $pengembalian =
            Pengembalian::findOrFail($id);

        $pengembalian->update([
            'kondisi_kembali' =>
                $request->kondisi_kembali,

            'denda' =>
                $request->denda ?? 0,
        ]);

        return redirect()
            ->route(
                'admin.pengembalian.index'
            )
            ->with(
                'success',
                'Data pengembalian berhasil diperbarui.'
            );
    }


    /**
     * ============================================================
     * HAPUS DATA PENGEMBALIAN
     * ============================================================
     *
     * Menghapus data pengembalian saja.
     *
     * Status peminjaman TETAP selesai.
     * Stok TETAP seperti setelah pengembalian.
     *
     * Jadi menghapus riwayat pengembalian tidak membuat
     * peminjaman kembali masuk ke Peminjaman Aktif.
     *
     * ============================================================
     */
    public function destroy($id)
    {
        try {

            DB::transaction(function () use ($id) {

                /*
                |--------------------------------------------------------------------------
                | Ambil data pengembalian
                |--------------------------------------------------------------------------
                */

                $pengembalian =
                    Pengembalian::findOrFail($id);

                /*
                |--------------------------------------------------------------------------
                | Hapus data pengembalian
                |--------------------------------------------------------------------------
                |
                | TIDAK mengubah status peminjaman.
                | TIDAK mengurangi stok.
                |
                | Karena status peminjaman sudah selesai dan alat
                | memang sudah dikembalikan.
                |--------------------------------------------------------------------------
                */

                $pengembalian->delete();
            });

            return redirect()
                ->route(
                    'admin.pengembalian.index'
                )
                ->with(
                    'success',
                    'Data pengembalian berhasil dihapus.'
                );

        } catch (Exception $e) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}