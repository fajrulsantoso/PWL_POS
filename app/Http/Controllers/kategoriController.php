<?php

namespace App\Http\Controllers;

use App\DataTables\KategoriDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class kategoriController extends Controller
{
      public function index(KategoriDataTable $dataTable)
      {
        return $dataTable->render('kategori.index');
      }   
    }

