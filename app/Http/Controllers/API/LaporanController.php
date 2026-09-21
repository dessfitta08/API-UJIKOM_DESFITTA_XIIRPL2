<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Gunakan nested eager loading: details.alat (atau detailpinjams.alat)
        $query = Peminjaman::with(['user', 'details.alat']);

        // Filter berdasarkan rentang tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $laporan = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Data laporan peminjaman berhasil ditampilkan.',
            'data'    => $laporan
        ], 200);
    }
}