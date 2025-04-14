<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\KategoriModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Redirector;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class kategoriController extends Controller
{
    public function index()
    {
        $page = (object) ['title' => 'Daftar kategori yang terdaftar dalam sistem.'];
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori'],
        ];

        return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => KategoriModel::all(), 'activeMenu' => 'kategori']);
    }

    public function list(Request $request): JsonResponse
    {
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');
        if ($request->kategori_id) $kategori->where('kategori_id', $request->kategori_id);
        return DataTables::of($kategori)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) {
                $btn = '<a href="' . url('/kategori/' . $kategori->kategori_id) . '" class="btn btn-info btnsm">Detail</a> ';
                $btn .= '<a href="' . url('/kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form method="POST" action="' . url('/kategori/' . $kategori->kategori_id) . '" style="display:inline;">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create(): View
    {
        $page = (object) ['title' => 'Tambah Kategori.'];
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori', 'Add']
        ];

        return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => 'kategori']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_kode' => 'required|string',
            'kategori_nama' => 'required|string',
        ]);

        KategoriModel::create([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan');
    }

    public function show(string $id): View
    {
        $kategori = KategoriModel::find($id);
        $page = (object) ['title' => 'Detail Kategori'];
        $breadcrumb = (object) [
            'title' => 'Detail Kategori',
            'list' => ['Home', 'Kategori', 'Detail'],
        ];

        return view('kategori.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => 'kategori']);
    }

    public function edit(string $id): View
    {
        $page = (object) ['title' => 'Edit Kategori'];
        $breadcrumb = (object) [
            'title' => 'Edit Kategori',
            'list' => ['Home', 'Kategori', 'Edit']
        ];

        return view('kategori.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => KategoriModel::find($id),
            'activeMenu' => 'kategori',
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'kategori_kode' => 'required|string',
            'kategori_nama' => 'required|string',
        ]);

        KategoriModel::find($id)->update([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!KategoriModel::find($id)) return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan.');
        KategoriModel::find($id)->delete();
        return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus.');        
    }



    
    public function create_ajax(): View
    {
        return view('kategori.create_ajax', ['kategori' => KategoriModel::all()]);
    }

    public function store_ajax(Request $request): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'kategori_kode' => 'required|string|max:6|regex:/^[A-Z0-9]+$/',
                'kategori_nama' => 'required|string|min:3|max:50|regex:/^[a-zA-Z\s]+$/',
            ]);
    
            if ($validator->fails()) {
                return Response::json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal.',
                    'message_field' => $validator->errors(),
                ]);
            }

            KategoriModel::create([
                'kategori_kode' => $request->kategori_kode,
                'kategori_nama' => $request->kategori_nama,
            ]);

            return Response::json(['status'  => true, 'message' => 'Data kategori berhasil disimpan']);
        }
        return redirect('/kategori');
    }
}
