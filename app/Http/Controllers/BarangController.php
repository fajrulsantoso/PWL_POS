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
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;


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
    
}