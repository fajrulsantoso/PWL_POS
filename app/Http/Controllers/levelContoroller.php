<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Level as LevelModel;

class Level extends Controller
{
    public function index()
    {
        $page = (object) ['title' => 'Daftar level yang terdaftar dalam sistem.'];
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level'],
        ];

    }
}