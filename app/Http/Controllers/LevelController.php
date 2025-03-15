<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;

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

        return view('level.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => 'level'
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
}
