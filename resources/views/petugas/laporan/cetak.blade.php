<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cetak Laporan Peminjaman Alat</title>

    <style>

        /* =====================================================
           PENGATURAN HALAMAN CETAK
        ===================================================== */

        @page {
            size: A4 landscape;
            margin: 15mm;
        }


        /* =====================================================
           DASAR
        ===================================================== */

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #1f2937;
            margin: 0;
            padding: 20px;
            background: #ffffff;
        }


        /* =====================================================
           TOMBOL
        ===================================================== */

        .no-print {
            margin-bottom: 25px;
        }

        .print-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-bottom: 20px;
        }

        .btn {
            border: none;
            padding: 9px 18px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cetak {
            background: #2563eb;
            color: #ffffff;
        }

        .btn-cetak:hover {
            background: #1d4ed8;
        }

        .btn-tutup {
            background: #64748b;
            color: #ffffff;
        }

        .btn-tutup:hover {
            background: #475569;
        }


        /* =====================================================
           HEADER LAPORAN
        ===================================================== */

        .header {
            text-align: center;
            border-bottom: 2px solid #1f2937;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0 0 5px 0;
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }

        .header p {
            margin: 3px 0;
            font-size: 12px;
            color: #374151;
        }

        .periode {
            margin-top: 8px !important;
            font-size: 11px !important;
            color: #4b5563 !important;
        }


        /* =====================================================
           TABEL
        ===================================================== */

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #cbd5e1;
            padding: 7px 8px;
            vertical-align: top;
        }


        /* HEADER TABEL */

        th {
            background-color: #1e293b !important;
            color: #ffffff !important;
            font-weight: 600;
            text-align: center;

            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }


        /* ISI TABEL */

        td {
            background-color: #ffffff;
            color: #1f2937;
        }


        /* =====================================================
           ALIGNMENT
        ===================================================== */

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .nowrap {
            white-space: nowrap;
        }


        /* =====================================================
           DETAIL ALAT
        ===================================================== */

        .detail-alat {
            margin: 0;
            padding-left: 16px;
        }

        .detail-alat li {
            margin-bottom: 3px;
        }


        /* =====================================================
           STATUS
        ===================================================== */

        .status {
            font-weight: 600;
        }


        /* =====================================================
           PESAN DATA KOSONG
        ===================================================== */

        .empty-data {
            text-align: center;
            padding: 15px;
            color: #6b7280;
        }


        /* =====================================================
           TANDA TANGAN
        ===================================================== */

        .footer {
            width: 250px;
            margin-top: 45px;
            margin-left: auto;
            text-align: center;
        }

        .footer p {
            margin: 4px 0;
        }

        .signature-space {
            height: 65px;
        }

        .nama-petugas {
            font-weight: 700;
            text-decoration: underline;
        }


        /* =====================================================
           RESPONSIVE UNTUK LAYAR
        ===================================================== */

        @media screen and (max-width: 900px) {

            body {
                padding: 12px;
                overflow-x: auto;
            }

            table {
                min-width: 900px;
            }

        }


        /* =====================================================
           MODE CETAK
        ===================================================== */

        @media print {

            body {
                margin: 0;
                padding: 0;
                font-size: 11px;
            }

            .no-print {
                display: none !important;
            }

            .header {
                margin-bottom: 15px;
            }

            .header h1 {
                font-size: 17px;
            }

            .header p {
                font-size: 11px;
            }

            th,
            td {
                padding: 6px 7px;
            }

            th {
                background-color: #1e293b !important;
                color: #ffffff !important;

                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .footer {
                margin-top: 35px;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

        }

    </style>

</head>


<body>


    {{-- =====================================================
         TOMBOL CETAK
    ====================================================== --}}

    <div class="no-print">

        <div class="print-buttons">

            <button
                type="button"
                onclick="window.print()"
                class="btn btn-cetak"
            >
                Cetak Sekarang
            </button>

            <button
                type="button"
                onclick="window.close()"
                class="btn btn-tutup"
            >
                Tutup
            </button>

        </div>

    </div>


    {{-- =====================================================
         HEADER LAPORAN
    ====================================================== --}}

    <div class="header">

        <h1>
            LAPORAN PEMINJAMAN DAN PENGEMBALIAN ALAT
        </h1>

        <p>
            Sistem Informasi Manajemen Peminjaman Alat
        </p>


        @if(request('tanggal_mulai') || request('tanggal_selesai'))

            <p class="periode">

                Periode:

                {{ request('tanggal_mulai') ?: '-' }}

                s/d

                {{ request('tanggal_selesai') ?: '-' }}

            </p>

        @endif

        @if(request('status'))

            <p class="periode">

                Status:

                {{ ucfirst(request('status')) }}

            </p>

        @endif

    </div>


    {{-- =====================================================
         TABEL LAPORAN
    ====================================================== --}}

    <table>

        <thead>

            <tr>

                <th style="width: 5%;">
                    No
                </th>

                <th style="width: 18%;">
                    Peminjam
                </th>

                <th style="width: 12%;">
                    Tgl Pinjam
                </th>

                <th style="width: 15%;">
                    Rencana Kembali
                </th>

                <th style="width: 12%;">
                    Status
                </th>

                <th style="width: 28%;">
                    Detail Alat
                </th>

                <th style="width: 10%;">
                    Denda
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($laporans as $index => $item)

                <tr>

                    {{-- NO --}}

                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>


                    {{-- PEMINJAM --}}

                    <td class="text-left">
                        {{ $item->user->name ?? '-' }}
                    </td>


                    {{-- TANGGAL PINJAM --}}

                    <td class="text-center nowrap">
                        {{ $item->tgl_pinjam ?? '-' }}
                    </td>


                    {{-- RENCANA KEMBALI --}}

                    <td class="text-center nowrap">
                        {{ $item->tgl_kembali_plan ?? '-' }}
                    </td>


                    {{-- STATUS --}}

                    <td class="text-center status">
                        {{ ucfirst($item->status ?? '-') }}
                    </td>


                    {{-- DETAIL ALAT --}}

                    <td class="text-left">

                        @if($item->detailPinjams->count())

                            <ul class="detail-alat">

                                @foreach($item->detailPinjams as $detail)

                                    <li>

                                        {{ $detail->alat->nama_alat ?? '-' }}

                                        ({{ $detail->jumlah }})

                                    </li>

                                @endforeach

                            </ul>

                        @else

                            -

                        @endif

                    </td>


                    {{-- DENDA --}}

                    <td class="text-right nowrap">

                        Rp
                        {{ number_format(
                            $item->pengembalian->denda ?? 0,
                            0,
                            ',',
                            '.'
                        ) }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="7"
                        class="empty-data"
                    >
                        Tidak ada data laporan.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- =====================================================
         TANDA TANGAN
    ====================================================== --}}

    <div class="footer">

        <p>
            Baleendah,
            {{ now()->format('d F Y') }}
        </p>

        <p>
            Petugas Pengelola,
        </p>

        <div class="signature-space"></div>

        <p class="nama-petugas">
            {{ auth()->user()->name }}
        </p>

    </div>


</body>

</html>