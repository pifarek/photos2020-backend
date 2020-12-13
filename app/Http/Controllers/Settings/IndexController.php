<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media\Media;

class IndexController extends Controller
{
    public function random()
    {
        Media::randomize();

        echo 'done.';
    }
}
