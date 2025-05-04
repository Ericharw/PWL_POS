<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use App\Models\StokModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Penjualan',
            'list'  => ['Home', 'Penjualan']
        ];

        $page = (object)[
            'title' => 'Daftar penjualan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $penjualan = PenjualanModel::with('user')->select('penjualan_id', 'penjualan_kode', 'pembeli', 'penjualan_tanggal', 'user_id');

        return DataTables::of($penjualan)
            ->addIndexColumn()
            ->addColumn('user_name', function ($penjualan) {
                return $penjualan->user ? $penjualan->user->nama : '-';
            })
            ->addColumn('aksi', function ($penjualan) {
                $btn  = '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show_ajax(string $id)
    {
        $penjualan = PenjualanModel::with(['detail.barang', 'user'])->find($id);

        if (!$penjualan) {
            return response()->json([
                'status' => false,
                'message' => 'Data penjualan tidak ditemukan'
            ], 404);
        }

        return view('penjualan.show_ajax', compact('penjualan'));
    }

    public function create_ajax()
    {
        $barang = BarangModel::all();
        return view('penjualan.create_ajax', compact('barang'));
    }

    public function edit_ajax($penjualan_id)
{
    // Ambil semua detail barang dalam 1 kode penjualan
    $penjualanDetails = PenjualanDetailModel::with('barang', 'penjualan.user')
                                ->where('penjualan_id', $penjualan_id)
                                ->get();

    if ($penjualanDetails->isEmpty()) {
        return response()->view('penjualan.edit_ajax', compact('penjualanDetails'));
    }

    $penjualan = PenjualanModel::all();
    $barang = BarangModel::select('barang_id', 'barang_nama')->get();
    $user = UserModel::select('user_id', 'nama')->get();

    return view('penjualan.edit_ajax', compact('penjualanDetails', 'penjualan', 'barang', 'user'));
}

    // Method untuk menyimpan penjualan baru menggunakan AJAX
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi input
            $rules = [
                'pembeli' => 'required|string|max:255',
                'penjualan_tanggal' => 'required|date',
                'detail.*.barang_id' => 'required|exists:m_barang,barang_id',
                'detail.*.jumlah' => 'required|integer|min:1',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $data['penjualan_tanggal'] = Carbon::parse($request->penjualan_tanggal)->format('Y-m-d H:i:s');

           // DB::beginTransaction();
            try {
                
                    $penjualan = PenjualanModel::create([
                        'user_id' => auth()->id(),
                        'pembeli' => $request->pembeli,
                        'penjualan_kode' => 'PJ' . now()->format('YmdHis'),
                        'penjualan_tanggal' => $data['penjualan_tanggal'],
                    ]);
               

                $totalHarga = 0;

                foreach ($request->detail as $item) {
                    $barang = BarangModel::findOrFail($item['barang_id']);
                
                    // Dapatkan stok
                   // $stok = StokModel::where('barang_id', $item['barang_id'])->first();
                
                    // Dapatkan supplier_id dari barang jika ada, atau set default/null
                    $supplier_id = $barang->supplier_id ?? 1; // <- sesuaikan jika kamu punya relasi
                
                    //if (!$stok) {
                        $stok = StokModel::create([
                            'barang_id' => $item['barang_id'],
                            'stok_jumlah' => 0,
                            'stok_tanggal' => now(),
                            'user_id' => auth()->id(),
                            'supplier_id' => $supplier_id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                   // }

                    // if ($stok->stok_jumlah < $item['jumlah']) {
                    //     DB::rollBack();
                    //     return response()->json([
                    //         'status' => false,
                    //         'message' => 'Stok barang ' . $barang->barang_nama . ' tidak mencukupi. Stok tersedia: ' . $stok->stok_jumlah,
                    //     ]);
                    // }

                    $subtotal = $barang->harga_jual * $item['jumlah'];
                    $totalHarga += $subtotal;

                    PenjualanDetailModel::create([
                        'penjualan_id' => $penjualan->penjualan_id,
                        'barang_id' => $item['barang_id'],
                        'jumlah' => $item['jumlah'],
                        'harga' => $subtotal,
                    ]);

                    //$this->kurangiStok($item['barang_id'], $item['jumlah']);
                }

                //$penjualan->total = $totalHarga;
                $penjualan->save();

                //DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil disimpan',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
                ]);
            }
        }

        return redirect()->back();
    }

    // Method untuk mengurangi stok barang yang terjual
    private function kurangiStok($barang_id, $jumlah)
    {
        $stokRecords = StokModel::where('barang_id', $barang_id)
            ->where('stok_jumlah', '>', 0)
            ->orderBy('stok_tanggal')
            ->get();

        $sisa = $jumlah;
        foreach ($stokRecords as $stok) {
            if ($sisa <= 0) break;

            if ($stok->stok_jumlah >= $sisa) {
                $stok->decrement('stok_jumlah', $sisa);
                $sisa = 0;
            } else {
                $sisa -= $stok->stok_jumlah;
                $stok->update(['stok_jumlah' => 0]);
            }
        }

        if ($sisa > 0) {
            StokModel::create([
                'barang_id' => $barang_id,
                'stok_jumlah' => -$sisa,
                'stok_tanggal' => now(),
                'user_id' => auth()->id()
            ]);
        }
    }

    public function update_ajax(Request $request, $penjualan_id)
{
    if ($request->ajax() || $request->wantsJson()) {

        $rules = [
            'user_id' => 'required|exists:m_user,user_id',
            'pembeli' => 'required|string|max:50',
            'penjualan_kode' => 'required|string|max:50|unique:t_penjualan,penjualan_kode,' . $penjualan_id . ',penjualan_id',
            'penjualan_tanggal' => 'required|date',
            'details' => 'required|array|min:1',
            'details.*.detail_id' => 'required|exists:t_penjualan_detail,detail_id',
            'details.*.barang_id' => 'required|exists:m_barang,barang_id',
            'details.*.jumlah' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        DB::beginTransaction();

        try {
            $penjualan = PenjualanModel::findOrFail($penjualan_id);
            $penjualan->update([
                'user_id' => $request->user_id,
                'pembeli' => $request->pembeli,
                'penjualan_kode' => $request->penjualan_kode,
                'penjualan_tanggal' => Carbon::parse($request->penjualan_tanggal)->format('Y-m-d H:i:s'),
            ]);

            foreach ($request->details as $detailData) {
                $penjualanDetail = PenjualanDetailModel::findOrFail($detailData['detail_id']);
                $jumlahLama = $penjualanDetail->jumlah;
                $jumlahBaru = $detailData['jumlah'];

                $barangLamaId = $penjualanDetail->barang_id;
                $barangBaruId = $detailData['barang_id'];

                $stokLama = StokModel::where('barang_id', $barangLamaId)->first();
                $stokBaru = StokModel::where('barang_id', $barangBaruId)->first();

                if (!$stokLama || !$stokBaru) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Stok barang tidak ditemukan.',
                    ]);
                }

                if ($barangLamaId != $barangBaruId) {
                    // Barang diganti, stok lama dikembalikan
                    $stokLama->stok_jumlah += $jumlahLama;
                    $stokLama->save();

                    // Cek stok barang baru cukup
                    if ($stokBaru->stok_jumlah < $jumlahBaru) {
                        DB::rollBack();
                        return response()->json([
                            'status' => false,
                            'message' => 'Stok barang ' . $stokBaru->barang->barang_nama . ' tidak mencukupi.',
                        ]);
                    }

                    // Kurangi stok barang baru
                    $stokBaru->stok_jumlah -= $jumlahBaru;
                    $stokBaru->save();

                } else {
                    // Barang tidak berubah, cukup hitung selisih
                    $selisih = $jumlahBaru - $jumlahLama;

                    if ($selisih > 0) {
                        // Jika menambah, cek stok cukup
                        if ($stokBaru->stok_jumlah < $selisih) {
                            DB::rollBack();
                            return response()->json([
                                'status' => false,
                                'message' => 'Stok barang ' . $stokBaru->barang->barang_nama . ' tidak mencukupi untuk penambahan.',
                            ]);
                        }
                        $stokBaru->stok_jumlah -= $selisih;
                    } elseif ($selisih < 0) {
                        // Jika mengurangi, kembalikan ke stok
                        $stokBaru->stok_jumlah += abs($selisih);
                    }
                    $stokBaru->save();
                }

                // Update detail penjualan
                $barangBaru = BarangModel::findOrFail($barangBaruId);
                $penjualanDetail->update([
                    'barang_id' => $barangBaru->barang_id,
                    'jumlah' => $jumlahBaru,
                    'harga' => $barangBaru->harga_jual * $jumlahBaru,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data Transaksi Penjualan berhasil diperbarui',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    return redirect('/');
}

    // Method untuk menghapus penjualan menggunakan AJAX
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $penjualan = PenjualanModel::find($id);

            if ($penjualan) {
                try {
                    $penjualan->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }



    public function destroy($id)
    {
        $penjualan = PenjualanModel::find($id);

        if (!$penjualan) {
            return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        try {
            $penjualan->delete();
            return redirect('/penjualan')->with('success', 'Data penjualan berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/penjualan')->with('error', 'Data penjualan gagal dihapus karena masih terkait dengan data lain');
        }
    }

    public function import()
    {
        return view('penjualan.import');
    }

    public function import_ajax(Request $request)
    {
        $rules = [
            'file_penjualan' => ['required', 'mimes:xlsx', 'max:10485760']
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_penjualan');

        try {
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();

            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        $insert[] = [
                            'user_id' => $value['A'],
                            'pembeli' => $value['B'],
                            'penjualan_kode' => $value['C'],
                            'penjualan_tanggal' => $value['D'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    PenjualanModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengimpor data: ' . $e->getMessage()
            ]);
        }
    }

    public function export_excel()
    {
        $penjualan = PenjualanModel::with(['detail.barang', 'user'])
            ->orderBy('penjualan_tanggal', 'desc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Barang');
        $sheet->setCellValue('C1', 'Kode Penjualan');
        $sheet->setCellValue('D1', 'Tanggal Penjualan');
        $sheet->setCellValue('E1', 'Jumlah');
        $sheet->setCellValue('F1', 'Harga');
        $sheet->setCellValue('G1', 'Yang Mencatat');

        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        $no = 1;
        $row = 2;

        foreach ($penjualan as $item) {
            if ($item->detail && $item->detail->count() > 0) {
                foreach ($item->detail as $detail) {
                    $sheet->setCellValue('A' . $row, $no++);
                    $sheet->setCellValue('B' . $row, $detail->barang->barang_nama ?? '-');
                    $sheet->setCellValue('C' . $row, $item->penjualan_kode);
                    $sheet->setCellValue('D' . $row, Carbon::parse($item->penjualan_tanggal)->format('d-m-Y'));
                    $sheet->setCellValue('E' . $row, $detail->jumlah ?? 0);
                    $sheet->setCellValue('F' . $row, $detail->harga ?? 0);
                    $sheet->setCellValue('G' . $row, $item->user->nama ?? '-');
                    $row++;
                }
            }
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setTitle('Data Penjualan');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Penjualan_' . now()->format('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $penjualan = PenjualanModel::with('detail.barang', 'user')
            ->orderBy('penjualan_tanggal', 'desc')
            ->get();

        $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);

        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);

        return $pdf->stream('Data Penjualan ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
