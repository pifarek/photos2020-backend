<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media\Tag;
use App\Http\Resources\Tag as TagResource;
use Illuminate\Http\Request;

class Tags extends Controller
{
    /**
     * Get list of Categories
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list(Request $request)
    {
        // Get Tags
        $tags = Tag::whereHas('media')->orderBy('name', 'asc')->get();

        return TagResource::collection($tags);
    }
}
