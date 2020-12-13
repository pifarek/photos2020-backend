<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MediaList;
use App\Http\Requests\Api\MediaGet;
use App\Models\Category;
use App\Models\Media\Media as MediaModel;
use App\Http\Resources\Media as MediaResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Media extends Controller
{
    private $per_page = 12;

    /**
     * Return list of Media items
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list(MediaList $request)
    {
        $request->validated();

        $page = (int)$request->input('page', 1);
        \DB::enableQueryLog();
        // Get Query
        $query = $this->buildMediaQuery([
            'category_id' =>    $request->input('category_id'),
            'order' =>          $request->input('order', 'desc'),
            'order_by' =>       $request->input('order_by', 'date'),
            'tags' =>           $request->input('tags'),
        ]);

        // But we want to know total number of elements
        $total = $query->count();

        // Let set the limit
        $query->limit($this->per_page)->offset(($page - 1) * $this->per_page);

        $media = $query->get();

        return MediaResource::collection($media)->additional([
            'total' => $total,
            'page' => $page,
            'pages' => ceil($total / $this->per_page)
        ]);
    }

    /**
     * Return selected media
     * @param MediaGet $request
     * @param int $media_id
     * @return MediaResource
     */
    public function get(MediaGet $request, int $media_id)
    {
        $request->validated();

        $media = MediaModel::find($media_id);
        if(!$media) {
            return response()->json(['data' => ''], 500);
        }

        // Increase View number
        $media->total_count++;
        $media->save();

        \DB::enableQueryLog();

        $params = [
            'category_id' =>    $request->input('category_id'),
            'order' =>          $request->input('order', 'desc'),
            'order_by' =>       $request->input('order_by', 'date'),
            'tags' =>           $request->input('tags'),
        ];

        $previous = null;
        $next = null;

        if($request->input('order_by') === 'random') {
            // Get Previous Media
            $params['order'] = 'desc';
            // Get Previous Media
            $queryPrevious = $this->buildMediaQuery($params);
            $queryPrevious->where('media.order_random', '<', $media->order_random);
            $queryPrevious->limit(1);

            // Get Next Media
            $params['order'] = 'asc';
            $queryNext = $this->buildMediaQuery($params);
            $queryNext->where('media.order_random', '>', $media->order_random);
            $queryNext->limit(1);

            $previous = $queryPrevious->first();
            $next = $queryNext->first();
        } elseif($request->input('order_by') === 'views') {
            // Get Previous Media
            $params['order'] = 'desc';
            // Get Previous Media
            $queryPrevious = $this->buildMediaQuery($params);
            $queryPrevious->where('media.total_count', '<', $media->total_count);
            $queryPrevious->limit(1);

            // Get Next Media
            $params['order'] = 'asc';
            $queryNext = $this->buildMediaQuery($params);
            $queryNext->where('media.total_count', '>', $media->total_count);
            $queryNext->limit(1);

            $previous = $queryPrevious->first();
            $next = $queryNext->first();
        } elseif($request->input('order_by') === 'date') {
            // Get Previous Media
            $params['order'] = 'desc';

            $queryPrevious = $this->buildMediaQuery($params);

            $queryPrevious->where(function($query) use ($media){
                $query->where(function($query) use($media) {
                    $query->where('media.taken_at', '=', $media->taken_at);
                    $query->where('media.id', '<', $media->id);
                });
                $query->orWhere(function($query) use($media) {
                    $query->where('media.taken_at', '!=', $media->taken_at);
                    $query->where('media.taken_at', '<', $media->taken_at);
                });
            });
            $queryPrevious->limit(1);

            // Get Next Media
            $params['order'] = $params['order'] === 'desc' ? 'asc' : 'desc';
            $queryNext = $this->buildMediaQuery($params);
            $queryNext->where(function($query) use ($media){
                $query->where(function($query) use($media) {
                    $query->where('media.taken_at', '=', $media->taken_at);
                    $query->where('media.id', '>', $media->id);
                });
                $query->orWhere(function($query) use($media) {
                    $query->where('media.taken_at', '!=', $media->taken_at);
                    $query->where('media.taken_at', '>', $media->taken_at);
                });
            });
            $queryNext->limit(1);

            $previous = $queryPrevious->first();
            $next = $queryNext->first();
        }

        //dd(\DB::getQueryLog());

        return (new MediaResource($media))->additional([
            'prev_id' => $previous ? $previous->id : null,
            'next_id' => $next ? $next->id : null,
        ]);
    }

    private function buildMediaQuery(array $params) {
        $default = [
            'category_id' => null,
            'order' => 'desc', // desc, asc
            'order_by' => 'date', // date, random, views
            'tags' => null
        ];

        foreach($default as $item => $value) {
            if(key_exists($item, $params)) {
                $default[$item] = $params[$item];
            }
        }

        $query = MediaModel::query();

        $query->select('media.*');

        // Set the Category
        if($default['category_id']) {
            $categories = Category::recursiveCategories(Category::where('id', $default['category_id'])->where('status', 'published')->get());
            $ids = $categories->count() ? $categories->pluck('id') : [$default['category_id']];

            $query->whereIn('media.category_id', $ids);
        }

        // Set the tags
        if(is_array($default['tags']) && sizeof($default['tags'])) {
            $tags = $default['tags'];

            $query->whereHas('tags', function(Builder $query) use ($tags) {
                $query->where(function($query) use($tags){
                    $query->whereIn('media_tag.tag_id', $tags);
                });
            });
        }

        // Set the order
        if($default['order'] && $default['order_by']) {
            switch($default['order_by']) {
                case 'random';
                    // Order by pseudo random
                    $query->orderBy('order_random', $default['order']);
                    break;

                case 'views';
                    // Order by number of views
                    $query->orderBy('total_count', $default['order']);
                    break;

                case 'date':
                default:
                    // Order by category taken date
                    $query->orderBy('media.taken_at', $default['order']);
                    break;
            }
        }

        $query->orderBy('media.id', $default['order']);

        return $query;
    }
}
