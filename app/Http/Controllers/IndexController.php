<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Media\Media;

class IndexController extends Controller
{
    public function index()
    {
        $popular = Media::orderBy('total_count', 'desc')->limit(12)->get();

        return view('index', ['popular' => $popular]);
    }
}
