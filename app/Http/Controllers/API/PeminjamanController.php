<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PeminjamanResource;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\DetailPinjam;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;

class PeminjamanController extends Controller
{
    public function index(): JsonResponse
    {
        $peminjaman = Peminjaman::with(['user', 'details.alat'])->latest()->get();
        return response()->json([
            'status' => 'success',
            'data' => PeminjamanResource::collection($peminjaman)
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tgl_kembali_plan' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.alat_id' => ['required', 'integer', 'exists:alat,id'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
        ], [
            'tgl_kembali_plan.required' => 'Tanggal rencana pengembalian wajib diisi.',
            'tgl_kembali_plan.date_format' => 'Format tanggal harus YYYY-MM-DD.',
            'tgl_kembali_plan.after_or_equal' => 'Tanggal rencana kembali tidak boleh di masa lalu.',
            'items.required' => 'Anda harus memilih minimal satu alat untuk dipinjam.',
            'items.*.alat_id.exists' => 'Alat yang dipilih tidak ditemukan.',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::create([
                'user_id' => $request->user()->id,
                'tgl_pinjam' => now()->toDateString(),
                'tgl_kembali_plan' => $validated['tgl_kembali_plan'],
                'status' => 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                DetailPinjam::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id' => $item['alat_id'],
                    'jumlah' => $item['jumlah'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Peminjaman berhasil diajukan. Menunggu persetujuan.',
                'data' => new PeminjamanResource($peminjaman->load('details.alat'))
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengajukan peminjaman: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Peminjaman $peminjaman): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => new PeminjamanResource($peminjaman->load(['user', 'details.alat']))
        ]);
    }

    public function approve(Peminjaman $peminjaman): JsonResponse
    {
        if ($peminjaman->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Peminjaman sudah diproses sebelumnya.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            foreach ($peminjaman->details as $detail) {
                $alat = Alat::lockForUpdate()->find($detail->alat_id);
                if (!$alat || $alat->stok < $detail->jumlah) {
                    $namaAlat = $alat ? $alat->nama_alat : 'Unknown';
                    throw new Exception("Stok alat '{$namaAlat}' tidak mencukupi.");
                }
                $alat->decrement('stok', $detail->jumlah);
            }

            $peminjaman->update(['status' => 'approved']);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Peminjaman berhasil disetujui.',
                'data' => new PeminjamanResource($peminjaman->load('details.alat'))
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function riwayat(Request $request): JsonResponse
    {
        $peminjaman = Peminjaman::where('user_id', $request->user()->id)
            ->with('details.alat')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => PeminjamanResource::collection($peminjaman)
        ]);
    }

    public function destroy(Peminjaman $peminjaman): JsonResponse
    {
        $peminjaman->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data peminjaman berhasil dihapus.'
        ]);
    }
}