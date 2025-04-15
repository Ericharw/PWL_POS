<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SupplierController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier']
        ];

        $page = (object) [
            'title' => 'Daftar supplier yang terdaftar dalam sistem',
        ];

        $activeMenu = 'supplier'; // untuk set menu yang sedang aktif

        return view('supplier.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list()
    {
        $suppliers = SupplierModel::select('supplier_id', 'supplier_nama', 'alamat', 'telepon');

        return DataTables::of($suppliers)->addIndexColumn()->addColumn('aksi', function ($supplier) {
            //$btn = '<a href="' . url('/supplier/' . $supplier->supplier_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                //$btn .= '<a href="' . url('/supplier/' . $supplier->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                //$btn .= '<form class="d-inline-block" method="POST" action="' .
                //    url('/supplier/' . $supplier->supplier_id) . '">' . csrf_field() . method_field('DELETE') .
                //    '<button type="submit" class="btn btn-danger btn-sm" 
                //    onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';
                $btn = '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Supplier',
            'list' => ['Home', 'Supplier', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah supplier baru',
        ];

        $activeMenu = 'supplier';

        return view('supplier.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:15'
        ]);

        SupplierModel::create([
            'supplier_nama' => $request->supplier_nama,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon
        ]);

        return redirect('/supplier')->with('success', 'Data supplier berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $breadcrumb = (object) [
            'title' => 'Detail Supplier',
            'list' => ['Home', 'Supplier', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail supplier',
        ];

        $activeMenu = 'supplier';

        $supplier = SupplierModel::findOrFail($id);

        return view('supplier.show', compact('breadcrumb', 'page', 'supplier', 'activeMenu'));
    }

    public function edit(string $id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Supplier',
            'list' => ['Home', 'Supplier', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit supplier',
        ];

        $activeMenu = 'supplier';

        $supplier = SupplierModel::findOrFail($id);

        return view('supplier.edit', compact('breadcrumb', 'page', 'supplier', 'activeMenu'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'supplier_nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:15'
        ]);

        SupplierModel::findOrFail($id)->update([
            'supplier_nama' => $request->supplier_nama,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon
        ]);

        return redirect('/supplier')->with('success', 'Data supplier berhasil diubah!');
    }

    public function destroy(string $id)
    {
        $supplier = SupplierModel::find($id);

        if (!$supplier) {
            return redirect('/supplier')->with('error', 'Data supplier tidak ditemukan!');
        }

        try {
            $supplier->delete();
            return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/supplier')->with('error', 'Data supplier gagal dihapus karena masih terdapat data terkait!');
        }
    }

    public function create_ajax()
    {
        return view('supplier.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_nama' => 'required|string|max:100',
                'alamat' => 'required|string|max:100',
                'telepon' => 'required|string'
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            // Jika validasi gagal, kembalikan respons JSON dengan error
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
    
            // Jika validasi berhasil, simpan data
            SupplierModel::create($request->all());
    
            return response()->json([
                'status' => true,
                'message' =>"Data berhasil ditambahkan"
            ]);
        }
    
        return redirect('/');
    }
    

    public function show_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);

        return view('supplier.show_ajax', ['supplier' => $supplier]);
    }

    public function edit_ajax(string $id)
    {
        // return "Berhasil";
        $supplier = SupplierModel::find($id);

        return view('supplier.edit_ajax', ['supplier' => $supplier]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_nama' => 'required|string|max:100',
                'supplier_alamat' => 'required|string|max:100',
                'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode,' . $id . ',supplier_id'
            ];

            $messages = [
                'supplier_kode.unique' => 'Kode Sudah Digunakan'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $errorMessage = 'Validasi Gagal';
                if ($validator->errors()->has('supplier_kode')) {
                    $errorMessage = 'Validasi Gagal (Kode Sudah Digunakan)';
                }

                return response()->json([
                    'status' => false,
                    'message' => $errorMessage,
                    'msgField' => $validator->errors()
                ]);
            }

            $supplier = SupplierModel::find($id);
            if ($supplier) {
                $supplier->update($request->all());
                return response()->json(['status' => true, 'message' => 'Data supplier berhasil diperbarui']);
            } else {
                return response()->json(['status' => false, 'message' => 'Data supplier tidak ditemukan']);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);

        return view('supplier.confirm_ajax', ['supplier' => $supplier]);
    }

    public function delete_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $supplier = SupplierModel::find($id);
        if ($supplier) {
            try {
                $supplier->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak dapat dihapus karena masih memiliki keterkaitan dengan data lain!'
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

    public function import()
    {
        return view('supplier.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_supplier' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_supplier');
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
                            'supplier_id' => $value['A'],
                            'supplier_kode' => $value['B'],
                            'supplier_nama' => $value['C'],
                            'supplier_alamat' => $value['D'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    SupplierModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data supplier berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_excel(Request $request)
{
    $supplier = SupplierModel::select('supplier_id', 'supplier_nama', 'alamat', 'telepon')->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Nama Supplier');
    $sheet->setCellValue('C1', 'Alamat');
    $sheet->setCellValue('D1', 'Telepon');

    $sheet->getStyle('A1:D1')->getFont()->setBold(true);

    // Isi data
    $no = 1;
    $baris = 2;
    foreach ($supplier as $value) {
        $sheet->setCellValue('A' . $baris, $no);
        $sheet->setCellValue('B' . $baris, $value->supplier_nama);
        $sheet->setCellValue('C' . $baris, $value->alamat);
        $sheet->setCellValue('D' . $baris, $value->telepon);
        $baris++;
        $no++;
    }

    foreach (range('A', 'D') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $sheet->setTitle('Data Supplier');
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data_Supplier_' . date('Y-m-d_His') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Pragma: public');

    $writer->save('php://output');
    exit;
    }

    public function export_pdf()
    {
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama', 'alamat', 'telepon')
            ->orderBy('supplier_id', 'asc')
            ->get();

        $pdf = Pdf::loadView('supplier.export_pdf', ['supplier' => $supplier]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->render();

        return $pdf->stream('Data_Supplier_' . date('Y-m-d_His') . '.pdf');
    }

}
