<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\SupplierModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

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



    public function create_ajax(): View
    {
        return view('supplier.create_ajax', ['supplier' => SupplierModel::all()]);
    }

    public function store_ajax(Request $request): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'supplier_kode' => 'required|string|min:3|max:6|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]+$/|unique:m_supplier,supplier_kode',
                'supplier_nama' => 'required|string|min:3|max:50|regex:/^[a-zA-Z\s.]+$/',
                'supplier_alamat' => 'required|string|min:10|max:100|regex:/^[a-zA-Z0-9\s.,-]+$/',
            ]);
    
            if ($validator->fails()) {
                return Response::json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal.',
                    'message_field' => $validator->errors(),
                ]);
            }

            SupplierModel::create([
                'supplier_kode' => $request->supplier_kode,
                'supplier_nama' => $request->supplier_nama,
                'supplier_alamat' => $request->supplier_alamat,
            ]);

            return Response::json(['status'  => true, 'message' => 'Data supplier berhasil disimpan']);
        }
        return redirect('/supplier');
    }

    public function edit_ajax(string $id): View
    {
        return view('supplier.edit_ajax', ['supplier' => SupplierModel::find($id)]);
    }

    public function update_ajax(Request $request, string $id): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'supplier_kode' => 'nullable|string|min:3|max:6|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]+$/',
                'supplier_nama' => 'required|string|min:3|max:50|regex:/^[a-zA-Z\s.]+$/',
                'supplier_alamat' => 'required|string|min:10|max:100|regex:/^[a-zA-Z0-9\s.,-]+$/',
            ]);

            if ($validator->fails()) return Response::json(['status' => false, 'message' => 'Validasi Gagal.', 'message_field' => $validator->errors()]);

            if (SupplierModel::find($id)) {
                SupplierModel::find($id)->update([
                    'supplier_kode' => $request->supplier_kode,
                    'supplier_nama' => $request->supplier_nama,
                    'supplier_alamat' => $request->supplier_alamat,
                ]);

                return Response::json(['status' => true, 'message' => 'Data berhasil diperbarui.']);
            } else {
                return Response::json(['status' => false, 'message' => 'Data tidak ditemukan.']);
            }
        }
        return redirect('/supplier');
    }

    public function confirm_ajax(string $id): View
    {
        return view('supplier.confirm_ajax', ['supplier' => SupplierModel::find($id)]);
    }

    public function delete_ajax(Request $request, string $id): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            if (SupplierModel::find($id)) {
                SupplierModel::find($id)->delete();
                return Response::json(['status' => true, 'message' => 'Data berhasil dihapus.']);
            } else {
                return Response::json(['status' => false, 'message' => 'Data tidak ditemukan.']);
            }
        }

        return redirect('/level');
    }
}

