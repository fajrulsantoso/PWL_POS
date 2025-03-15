<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\SupplierModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public function index()
    {
        $page = (object) ['title' => 'Daftar supplier yang terdaftar dalam sistem.'];
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier'],
        ];

        return view('supplier.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => SupplierModel::all(), 'activeMenu' => 'supplier']);
    }

    public function list(Request $request): JsonResponse
    {
        $supplier = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_alamat');
        if ($request->supplier_id) $supplier->where('supplier_id', $request->supplier_id);
        return DataTables::of($supplier)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) {
                $btn = '<a href="' . url('/supplier/' . $supplier->supplier_id) . '" class="btn btn-info btnsm">Detail</a> ';
                $btn .= '<a href="' . url('/supplier/' . $supplier->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form method="POST" action="' . url('/supplier/' . $supplier->supplier_id) . '" style="display:inline;">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create(): View
    {
        $page = (object) ['title' => 'Tambah Supplier.'];
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier', 'Add']
        ];

        return view('supplier.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => 'supplier']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_kode' => 'required|string',
            'supplier_nama' => 'required|string',
            'supplier_alamat' => 'required|string',
        ]);

        SupplierModel::create([
            'supplier_kode' => $request->supplier_kode,
            'supplier_nama' => $request->supplier_nama,
            'supplier_alamat' => $request->supplier_alamat,
        ]);

        return redirect('/supplier')->with('success', 'Data supplier berhasil disimpan');
    }

    public function show(string $id): View
    {
        $supplier = SupplierModel::find($id);
        $page = (object) ['title' => 'Detail Supplier'];
        $breadcrumb = (object) [
            'title' => 'Detail Supplier',
            'list' => ['Home', 'Supplier', 'Detail'],
        ];

        return view('supplier.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => 'supplier']);
    }

    public function edit(string $id): View
    {
        $page = (object) ['title' => 'Edit Supplier'];
        $breadcrumb = (object) [
            'title' => 'Edit Supplier',
            'list' => ['Home', 'Supplier', 'Edit']
        ];

        return view('supplier.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'supplier' => SupplierModel::find($id),
            'active_menu' => 'supplier',
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'supplier_kode' => 'required|string',
            'supplier_nama' => 'required|string',
            'supplier_alamat' => 'required|string',
        ]);

        SupplierModel::find($id)->update([
            'supplier_kode' => $request->supplier_kode,
            'supplier_nama' => $request->supplier_nama,
            'supplier_alamat' => $request->supplier_alamat,
        ]);

        return redirect('/supplier')->with('success', 'Data supplier berhasil diubah');
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!SupplierModel::find($id)) return redirect('/supplier')->with('error', 'Data supplier tidak ditemukan.');
        SupplierModel::find($id)->delete();
        return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus.');        
    }
}
