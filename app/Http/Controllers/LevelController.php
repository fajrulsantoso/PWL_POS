<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Redirector;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class LevelController extends Controller
{
    public function index()
    {
        $page = (object) ['title' => 'Daftar level yang terdaftar dalam sistem.'];
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level'],
        ];

        return view('level.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => LevelModel::all(),
            'activeMenu' => 'level'
        ]);
    }

    public function list(Request $request): JsonResponse
    {
        $level = LevelModel::select('level_id', 'level_kode', 'level_nama');
        if ($request->level_id) {
            $level->where('level_id', $request->level_id);
        }
        
        return DataTables::of($level)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) {
                $btn = '<a href="' . url('/level/' . $level->level_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form method="POST" action="' . url('/level/' . $level->level_id) . '" style="display:inline;">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

 
    
    public function create(): View
    {
        $page = (object) ['title' => 'Tambah Level.'];
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level', 'Add']
        ];
    
        // Ambil data level dari database
        $level = LevelModel::select('level_id', 'level_nama')->get();
    
        return view('level.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => 'level',
            'level' => $level // Kirim variabel $level ke view
        ]);
    }
    

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'level_kode' => 'required|max:3',
            'level_nama' => 'required|string',
        ]);

        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);

        return redirect('/level')->with('success', 'Data level berhasil disimpan');
    }

    public function show(string $id): View
    {
        $level = LevelModel::findOrFail($id);
        $page = (object) ['title' => 'Detail Level'];
        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list' => ['Home', 'Level', 'Detail'],
        ];

        return view('level.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => 'level'
        ]);
    }

    public function edit(string $id): View
    {
        $page = (object) ['title' => 'Edit Level'];
        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];

        return view('level.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => LevelModel::findOrFail($id),
            'activeMenu' => 'level',
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'level_kode' => 'required|max:3',
            'level_nama' => 'required|string',
        ]);

        LevelModel::findOrFail($id)->update([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);

        return redirect('/level')->with('success', 'Data level berhasil diubah');
    }

    public function destroy(string $id): RedirectResponse
    {
        $level = LevelModel::find($id);
        if (!$level) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan.');
        }

        $level->delete();
        return redirect('/level')->with('success', 'Data level berhasil dihapus.');
    }
   

    public function create_ajax()
    {
        return view('level.create_ajax')->with('level', LevelModel::select('level_id', 'level_nama')->get());
    }
    
    public function store_ajax(Request $request)
    {
        // Cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|min:2|max:10|unique:m_level,level_kode',
                'level_nama' => 'required|string|min:3|max:100|unique:m_level,level_nama',
            ];
    
            // Gunakan Validator untuk validasi input
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'message_field' => $validator->errors(),
                ]);
            }
    
            // Simpan data jika validasi sukses
            LevelModel::create([
                'level_kode' => $request->level_kode,
                'level_nama' => $request->level_nama,
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Data level berhasil disimpan',
            ]);
        }
    }
// Menampilkan halaman form edit level menggunakan AJAX
public function edit_ajax(string $id): View
{
    return view('level.edit_ajax', ['level' => LevelModel::find($id)]);
}
// Memproses update data level menggunakan AJAX
public function update_ajax(Request $request, $id)
{
    $rules = [
        'level_nama' => 'required|max:50|unique:t_level,level_nama,' . $id . ',level_id',
    ];

    $validator = Validator::make($request->all(), $rules);

    // Jika validasi gagal, kembalikan response JSON
    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validasi gagal.',
            'message_field' => $validator->errors()
        ]);
    }

    // Cari level berdasarkan ID
    $level = LevelModel::find($id);
    if (!$level) {
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }

    // Update data
    $level->level_nama = $request->level_nama;
    $level->save();

    return response()->json([
        'status' => true,
        'message' => 'Data berhasil diperbarui'
    ]);
}

// Menampilkan halaman konfirmasi penghapusan level menggunakan AJAX
public function confirm_ajax(string $id)
{
    $level = LevelModel::find($id);
    return view('level.confirm_ajax', ['level' => $level]);
}

// Memproses penghapusan data level menggunakan AJAX
public function delete_ajax(Request $request, $id)
{
    // Cek apakah request dari AJAX
    if ($request->ajax() || $request->wantsJson()) {
        $level = LevelModel::find($id);
        if ($level) {
            $level->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    return redirect('/');
}  

public function import(): View
{
    return view('level.import');
}

public function import_ajax(Request $request): JsonResponse|Redirector|RedirectResponse
{
    if ($request->ajax() || $request->wantsJson()) {
        $validator = Validator::make($request->all(), [
            'file_level' => ['required', 'mimes:xlsx', 'max:1024'],
        ]);

        if ($validator->fails()) return Response::json(['status' => false, 'message' => 'Validasi Gagal.', 'message_field' => $validator->errors()]);
        
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $data = $reader->load($request->file('file_level')->getRealPath())->getActiveSheet()->toArray(null, false, true, true);
        $insert = [];

        if (count($data) > 1) {
            foreach ($data as $rows => $value) {
                if ($rows > 1) {
                    $insert[] = [
                        'level_kode' => $value['A'],
                        'level_nama' => $value['B'],
                        'created_at' => now(),
                    ];
                }
            }

            if (count($insert) > 0) LevelModel::insertOrIgnore($insert);
            return Response::json(['status' => true, 'message' => 'Data berhasil diimpor.']);
        } else {
            return Response::json(['status' => false, 'message' => 'Tidak ada data yang diimpor.']);
        }
    }

    return redirect('/level');
}

public function export_excel()
{
    $level = LevelModel::select('level_kode', 'level_nama')->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Kode Level');
    $sheet->setCellValue('C1', 'Nama Level');
    $sheet->getStyle('A1:C1')->getFont()->setBold(true);

    $no = 1;
    $baris = 2;
    foreach ($level as $key => $value) {
        $sheet->setCellValue("A{$baris}", $no);
        $sheet->setCellValue("B{$baris}", $value->level_kode);
        $sheet->setCellValue("C{$baris}", $value->level_nama);
        $baris++;
        $no++;
    }

    foreach(range('A', 'C') as $column) $sheet->getColumnDimension($column)->setAutoSize(true);
    $sheet->setTitle('Data Level');
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . 'Data Level ' . date('Y-m-d_H-i-s') . '.xlsx' . '"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');

    $writer->save('php://output');
    exit; 
}

public function export_pdf(): HttpResponse
{
    $level = LevelModel::select('level_kode', 'level_nama')->orderBy('level_kode')->get();

    $pdf = Pdf::loadView('level.export-pdf', ['level' => $level]);
    $pdf->setPaper('a4', 'portrait');
    $pdf->setOption('isRemoteEnabled', true);
    $pdf->render();

    return $pdf->stream('Data Level ' . date('Y-m-d H:i:s') . '.pdf');
}
}



