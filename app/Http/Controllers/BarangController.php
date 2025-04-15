<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\View\View;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use App\Models\KategoriModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response as HttpResponse;


class BarangController extends Controller
{
    public function index()
    {
        $page = (object) ['title' => 'Daftar barang yang terdaftar dalam sistem'];
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];

        return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => KategoriModel::all(), 'activeMenu' => 'barang']);
    }

    public function list(Request $request)
    {
        $barang = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual', 'kategori_id')->with('kategori');
        if ($request->kategori_id) $barang->where('kategori_id', $request->kategori_id);

        return DataTables::of($barang)
            ->addIndexColumn()
            ->addColumn('aksi', function ($barang) {
                $btn = '<a href="' . url('/barang/' . $barang->barang_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/barang/' . $barang->barang_id) . '">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $page = (object) ['title' => 'Tambah Barang baru'];
        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];

        return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => KategoriModel::all(), 'activeMenu' => 'barang']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
            'kategori_id' => 'required|integer'
        ]);

        BarangModel::create([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'kategori_id' => $request->kategori_id
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
    }

    public function show(string $id)
    {
        $barang = BarangModel::with('kategori')->find($id);
        $page = (object) ['title' => 'Detail Barang'];
        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];

        return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => 'barang']);
    }

    public function edit(string $id)
    {
        $page = (object) ['title' => 'Edit Barang'];
        $breadcrumb = (object) [
            'title' => 'Edit Barang',
            'list' => ['Home', 'Barang', 'Edit']
        ];

        return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => BarangModel::find($id), 'kategori' => KategoriModel::all(), 'activeMenu' => 'barang']);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'barang_kode' => 'required|string|min:3|',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
            'kategori_id' => 'required|integer'
        ]);

        BarangModel::find($id)->update([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'kategori_id' => $request->kategori_id
        ]);

        return redirect('/barang')->with('succes', 'Data barang berhasil diubah');
    }

    public function destroy(string $id): RedirectResponse
    {
        $check = BarangModel::find($id);
        if (!$check) return redirect('/barang')->with('error', 'Data barang tidak ditemukan');

        try {
            BarangModel::destroy($id);
            return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
        } catch (Exception $e) {
            Log::error($e);
            return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }



    public function create_ajax(): View
    {
        return view('barang.create_ajax', ['kategori' => KategoriModel::all()]);
    }

    public function store_ajax(Request $request): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'barang_kode' => 'required|string|max:6|regex:/^[A-Z0-9]+$/|unique:m_barang,barang_kode',
                'barang_nama' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/',
                'harga_beli' => 'required|integer',
                'harga_jual' => 'required|integer|gte:harga_beli',
                'kategori_id' => 'required|exists:m_kategori,kategori_id'
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal.',
                    'message_field' => $validator->errors(),
                ]);
            }

            BarangModel::create([
                'barang_kode' => $request->barang_kode,
                'barang_nama' => $request->barang_nama,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'kategori_id' => $request->kategori_id
            ]);

            return Response::json(['status'  => true, 'message' => 'Data kategori berhasil disimpan']);
        }
        return redirect('/barang');
    }

    public function edit_ajax(string $id): View
    {
        return view('barang.edit_ajax', ['barang' => BarangModel::find($id), 'kategori' => KategoriModel::all()]);
    }

    public function update_ajax(Request $request, string $id): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'barang_kode' => 'nullable|string|max:6|regex:/^[A-Z0-9]+$/|unique:m_barang,barang_kode,' . $id . ',barang_id',
                'barang_nama' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/',
                'harga_beli' => 'required|integer',
                'harga_jual' => 'required|integer|gte:harga_beli',
                'kategori_id' => 'required|exists:m_kategori,kategori_id'
            ]);

            if ($validator->fails()) return Response::json(['status' => false, 'message' => 'Validasi Gagal.', 'message_field' => $validator->errors()]);

            $barang = BarangModel::find($id);
            if ($barang) {
                $barang->update([
                    'barang_kode' => $request->barang_kode,
                    'barang_nama' => $request->barang_nama,
                    'harga_beli' => $request->harga_beli,
                    'harga_jual' => $request->harga_jual,
                    'kategori_id' => $request->kategori_id
                ]);

                return Response::json(['status' => true, 'message' => 'Data berhasil diperbarui.']);
            } else {
                return Response::json(['status' => false, 'message' => 'Data tidak ditemukan.']);
            }
        }
        return redirect('/barang');
    }

    public function confirm_ajax(string $id): View
    {
        return view('barang.confirm_ajax', ['barang' => BarangModel::find($id), 'kategori' => KategoriModel::all()]);
    }

    public function delete_ajax(Request $request, string $id): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            if (BarangModel::find($id)) {
                BarangModel::find($id)->delete();
                return Response::json(['status' => true, 'message' => 'Data berhasil dihapus.']);
            } else {
                return Response::json(['status' => false, 'message' => 'Data tidak ditemukan.']);
            }
        }

        return redirect('/barang');
    }


    public function import(): View
    {
        return view('barang.import');
    }

    public function import_ajax(Request $request): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'file_barang' => ['required', 'mimes:xlsx', 'max:1024'],
            ]);

            if ($validator->fails()) return Response::json(['status' => false, 'message' => 'Validasi Gagal.', 'message_field' => $validator->errors()]);
            
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $data = $reader->load($request->file('file_barang')->getRealPath())->getActiveSheet()->toArray(null, false, true, true);
            $insert = [];

            if (count($data) > 1) {
                foreach ($data as $rows => $value) {
                    if ($rows > 1) {
                        $insert[] = [
                            'kategori_id' => $value['A'],
                            'barang_kode' => $value['B'],
                            'barang_nama' => $value['C'],
                            'harga_beli' => $value['D'],
                            'harga_jual' => $value['E'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) BarangModel::insertOrIgnore($insert);
                return Response::json(['status' => true, 'message' => 'Data berhasil diimpor.']);
            } else {
                return Response::json(['status' => false, 'message' => 'Tidak ada data yang diimpor.']);
            }
        }

        return redirect('/barang');
    }

    public function export_excel(): never
    {
        $barang = BarangModel::select('kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
            ->orderBy('kategori_id')
            ->with('kategori')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Harga Beli');
        $sheet->setCellValue('E1', 'Harga Jual');
        $sheet->setCellValue('F1', 'Kategori');
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $number = 1;
        $rows = 2;
        foreach($barang as $key => $value) {
            $sheet->setCellValue("A{$rows}" . $rows, $number);
            $sheet->setCellValue("B{$rows}", $value->barang_kode);
            $sheet->setCellValue("C{$rows}", $value->barang_nama);
            $sheet->setCellValue("D{$rows}", $value->harga_beli);
            $sheet->setCellValue("E{$rows}", $value->harga_jual);
            $sheet->setCellValue("F{$rows}", $value->kategori->kategori_nama);
            $rows++;
            $number++;
        };

        foreach(range('A', 'F') as $column) $sheet->getColumnDimension($column)->setAutoSize(true);
        $sheet->setTitle('Data Barang');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . 'Data Barang ' . date('Y-m-d H:i:s') . '.xlsx' . '"');
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
        $barang = BarangModel::select('kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
            ->orderBy('kategori_id')
            ->orderBy('barang_kode')
            ->with('kategori')
            ->get();
    
        $pdf = Pdf::loadView('barang.export-pdf', ['barang' => $barang]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();
    
        return $pdf->stream('Data Barang ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
