<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StorePhotoRequest;
use App\Http\Requests\Media\StoreVideoRequest;
use App\Http\Requests\Media\UpdatePhotoRequest;
use App\Http\Requests\Media\UpdateVideoRequest;
use App\Models\Media\Media;
use App\Models\Category;
use App\Models\Media\Photo;
use App\Models\Media\Youtube;
use App\Models\Media\Tag;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $category_id = $request->input('category', null);
        $orderMethod = $request->input('order');

        switch($orderMethod) {
            case 'newest';
                $orderBy = 'created_at';
                $order = 'desc';
                break;
            case 'oldest';
                $orderBy = 'created_at';
                $order = 'asc';
                break;
            default:
                $orderBy = 'created_at';
                $order = 'desc';
        }

        // Get the categories
        $categories = Category::recursiveCategoriesArray(Category::whereNull('parent_id')->get());

        $query = Media::query();

        // If category is selected
        if($category_id) {
            $query->where('category_id', $category_id);
        }

        // Order
        $query->orderBy($orderBy, $order);

        $media = $query->paginate(24);

        return view('media.index', [
            'media' => $media,
            'categories' => $categories,
            'category_id' => $category_id,
            'order' => $orderMethod
        ]);
    }

    public function create()
    {
        $categories = Category::recursiveCategoriesArray(Category::whereNull('parent_id')->orderBy('name', 'asc')->get());

        // Get tags
        $tags = Tag::all();

        return view('media.create', ['categories' => $categories, 'tags' => $tags]);
    }

    /**
     * Convert Photo and add in into Database
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePhoto(StorePhotoRequest $request)
    {
        $request->validated();

        $filename = $request->input('filename');
        $path = 'upload/temporary/' . $filename;

        // Store Media informations
        $media = new Media();
        $media->filename = $request->input('filename');
        $media->name = $request->input('name');
        $media->description = $request->input('description');
        $media->category_id = $request->input('category_id');
        $media->taken_at = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('taken_at'));
        $media->type = 'photo';
        $media->save();

        // Add Tags
        $this->attachTags($media, $request->input('tags') ?? []);

        // Store Media Photo informations
        $photo = new Photo();
        $media->photo()->save($photo);

        // Move and resize files
        $photo = Image::make($path);

        // Upload "big" image
        $photo->resize(1920, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save('upload/images/f/' . $filename);

        // Upload medium image
        $photo->resize(1280, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save('upload/images/m/' . $filename);

        // Upload small
        $photo->crop(640, 480)->save('upload/images/s/' . $filename);

        // Remove temporary file
        \File::delete($path);

        // Generate new pseudo-random order
        Media::randomize();

        session()->put('last_date', $request->input('taken_at'));

        return redirect()->route('media.create', ['category' => $media->category_id])->with('success', 'New Media has been successfully created.');
    }

    /**
     * Convert Photo and add in into Database
     * @param StoreVideoRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeVideo(StoreVideoRequest $request)
    {
        $request->validated();

        $filename = uniqid(null, true) . '.jpg';

        // Store Media informations
        $media = new Media();
        $media->filename = $filename;
        $media->name = $request->input('name');
        $media->description = $request->input('description');
        $media->category_id = $request->input('category_id');
        $media->taken_at = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('taken_at'));
        $media->type = 'youtube';
        $media->save();

        // Add Tags
        $this->attachTags($media, $request->input('tags') ?? []);

        // Store Media informations
        $youtube = new Youtube();
        $youtube->code = Youtube::getCode($request->input('link'));
        $media->youtube()->save($youtube);

        // Move and resize files
        $photo = Image::make('https://img.youtube.com/vi/' . $youtube->code . '/0.jpg');

        // Upload "big" image
        $photo->resize(1920, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save('upload/images/f/' . $filename);

        // Upload medium image
        $photo->resize(1280, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save('upload/images/m/' . $filename);

        // Upload small
        $photo->crop(640, 480)->save('upload/images/s/' . $filename);

        // Generate new pseudo-random order
        Media::randomize();

        session()->put('last_date', $request->input('taken_at'));

        return redirect()->route('media.create', ['category' => $media->category_id])->with('success', 'New Media has been successfully created.');
    }

    /**
     * Attach tags to Media
     * @param Media $media
     * @param array $tags
     */
    private function attachTags(Media $media, array $tags)
    {
        $media->tags()->detach();

        foreach($tags as $tag_id) {
            $tagObject = Tag::find($tag_id);
            if($tagObject) {
                $media->tags()->attach($tagObject);
            }
        }
    }

    /**
     * Edit selected Media
     * @param int $media_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(int $media_id)
    {
        $media = Media::find($media_id);

        if(!$media) {
            return redirect()->route('media.index');
        }

        // Get Categories
        $categories = Category::recursiveCategoriesArray(Category::whereNull('parent_id')->orderBy('name', 'asc')->get());

        // Get tags
        $tags = Tag::all();

        return view('media.edit', [
            'media' => $media,
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    /**
     * @param UpdatePhotoRequest $request
     * @param int $media_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePhoto(UpdatePhotoRequest $request, int $media_id)
    {
        $media = Media::find($media_id);

        if(!$media) {
            return redirect()->route('media.index');
        }

        $request->validated();

        $media->name = $request->input('name');
        $media->description = $request->input('description');
        $media->category_id = $request->input('category_id');
        $media->taken_at = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('taken_at'));
        $media->save();

        $this->attachTags($media, $request->input('tags') ?? []);

        return redirect()->route('media.index')->with('success', 'Selected Media has been successfully updated.');
    }

    /**
     * @param UpdateVideoRequest $request
     * @param int $media_id
     * @return \Illuminate\Http\RedirectResponse
     */
    private function updateYoutubeVideo(UpdateVideoRequest $request, int $media_id)
    {
        $media = Media::find($media_id);

        if(!$media) {
            return redirect()->route('media.index');
        }

        $request->validated();

        $media->name = $request->input('name');
        $media->description = $request->input('description');
        $media->category_id = $request->input('category_id');
        $media->taken_at = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('taken_at'));
        $media->save();

        $this->attachTags($media, $request->input('tags') ?? []);

        return redirect()->route('media.index')->with('success', 'Selected Media has been successfully updated.');
    }

    /**
     * Upload temporary image
     * @param Request $request
     */
    public function temporary(Request $request)
    {
        $validation = \Validator::make($request->all(), ['photo' => ['file', 'mimetypes:image/jpeg,image/png']]);

        if($validation->fails()) {
            return response()->json(['status' => 'err']);
        }

        $filename = uniqid(null, true) . '.jpg';

        $photo = $request->file('photo');
        $photo->move('upload/temporary', $filename);

        return response()->json(['status' => 'ok', 'filename' => $filename]);
    }

    /**
     * Remove selected media
     * @param int $media_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(int $media_id)
    {
        $media = Media::find($media_id);
        if($media) {
            $media->delete();
        }

        return response()->json(['status' => 'ok']);
    }
}
