<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\View\View;
use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Menampilkan halaman awal user
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all(); // Ambil semua level
        $activeMenu = 'user';

        return view('user.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:t_user,username',
            'nama' => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer',
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id,
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }


    // Menampilkan daftar pengguna
    public function index()
    {
        $page = (object) ['title' => 'Daftar pengguna yang terdaftar dalam sistem.'];
        $breadcrumb = (object) [
            'title' => 'Daftar Pengguna',
            'list' => ['Home', 'User']
        ];

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => LevelModel::all(), 'activeMenu' => 'user']);
    }

    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        // $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
        //     ->with('level');
        // //filter data user berdasarkan level_id
        // if ($request->level_id) $users->where('level_id', $request->level_id);
        // $users = $users->get();
        // return DataTables::of($users)
        //     // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
        //     ->addIndexColumn()
        //     ->addColumn('aksi', function ($user) { // menambahkan kolom aksi
        //         // $btn = '<a href="' . url('/user/' . $user->user_id) . '" class="btn btn-info btnsm">Detail</a> ';
        //         // $btn .= '<a href="' . url('/user/' . $user->user_id . '/edit') . '" class="btn btnwarning btn-sm">Edit</a> ';
        //         // $btn .= '<form class="d-inline-block" method="POST" action="' .
        //         //     url('/user/' . $user->user_id) . '">'
        //         //     . csrf_field() . method_field('DELETE') .
        //         //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return
        //         // confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';
        //         // return $btn;
        //     })
        //     ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
        //     ->make(true);
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
        ->with('level');

    // Filter data user berdasarkan level_id jika ada
    if ($request->level_id) {
        $users->where('level_id', $request->level_id);
    }

    return DataTables::of($users)
        ->addIndexColumn() // Menambahkan kolom index / nomor urut
        ->addColumn('aksi', function ($user) {
            // Tombol aksi untuk modal detail, edit, dan hapus
            $btn = '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
            
            return $btn;
        })
        ->rawColumns(['aksi']) // Memberitahu DataTables bahwa kolom 'aksi' berisi HTML
        ->make(true);

    }


    //menapilkan data show 
    public function show(string $id): View
    {
        $user = UserModel::with('level')->find($id);
        $page = (object) ['title' => 'Detail Pengguna'];
        $breadcrumb = (object) [
            'title' => 'Detail Pengguna',
            'list' => ['Home', 'User', 'Detail'],
        ];

        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => 'user']);
    }


    public function edit(string $id): View
    {
        $page = (object) ['title' => 'Edit pengguna'];
        $breadcrumb = (object) [
            'title' => 'Edit Pengguna',
            'list' => ['Home', 'User', 'Edit']
        ];

        return view('user.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => UserModel::find($id),
            'level' => LevelModel::all(),
            'activeMenu' => 'user',
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:t_user,username,' . $id . ',user_id',
            'nama' => 'required|string|max:100',
            'password' => 'nullable|min:5',
            'level_id' => 'required|integer',
        ]);

        UserModel::find($id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!UserModel::find($id)) return redirect('/user')->with('error', 'Data pengguna tidak ditemukan.');
        try {
            UserModel::destroy($id);
            return redirect('/user')->with('success', 'Data pengguna berhasil dihapus.');
        } catch (QueryException $exception) {
            return redirect('/user')->with('error', 'Data pengguna gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini.');
        }
    }

    public function create_ajax()
    {
        return view('user.create_ajax')->with('level', LevelModel::select('level_id', 'level_nama')->get());
    }
    public function store_ajax(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:t_user,username',
                'nama' => 'required|string|max:100',
                'password' => 'required|min:6',
            ];

            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'message_field' => $validator->errors(),
                ]);
            }

            // Simpan data jika validasi sukses
            UserModel::create([
                'level_id' => $request->level_id,
                'username' => $request->username,
                'nama' => $request->nama,
                'password' => bcrypt($request->password), // Hash password untuk keamanan
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan',
            ]);
        }
    }
   
      //menampilkan halaman form edit user ajax

      public function edit_ajax(string $id)
      {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
    }


    public function update_ajax(Request $request, $id)
    {
        $rules = [
            'level_id' => 'required|integer',
            'username' => 'required|max:20|unique:t_user,username,' . $id . ',user_id',
            'nama' => 'required|max:100',
            'password' => 'nullable|min:6|max:20'
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
    
        // Cari user berdasarkan ID
        $user = UserModel::find($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    
        // Update data
        $user->level_id = $request->level_id;
        $user->username = $request->username;
        $user->nama = $request->nama;
    
        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
    
        $user->save();
    
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diperbarui'
        ]);
    }
    

}
