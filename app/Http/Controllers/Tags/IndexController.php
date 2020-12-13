<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media\Tag;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();

        return view('tags.index', ['tags' => $tags]);
    }

    /**
     * Add a new Tag
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $request->validate(['name' => ['required', 'min:3', 'unique:App\Models\Media\Tag,name']]);

        $name = $request->input('name');

        $tag = new Tag();
        $tag->name = ucfirst($name);
        $tag->save();

        return response()->json(['status' => 'ok', 'tag_id' => $tag->id]);
    }

    /**
     * @param int $tag_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(int $tag_id)
    {
        $tag = Tag::find($tag_id);
        if($tag) {
            $tag->delete();
        }

        return response()->json(['status' => 'ok']);
    }
}
