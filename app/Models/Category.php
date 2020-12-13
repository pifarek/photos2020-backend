<?php

namespace App\Models;

use App\Models\Media\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Category extends Model
{
    protected $table = 'categories';
    protected $dates = ['created_at', 'updated_at'];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'category_id', 'id');
    }

    /**
     * Delete category with all containing Media
     * @return bool|void|null
     * @throws \Exception
     */
    public function delete()
    {
        // Remove all children categories
        foreach($this->children() as $category) {
            $category->delete();
        }

        // Remove all containing media
        foreach($this->media as $media) {
            $media->delete();
        }


        parent::delete();
    }

    /**
     * Return Array of recursive Categories
     * @param Collection $categories
     * @param int $level
     * @param int $exclude_category_id
     * @return Collection
     */
    public static function recursiveCategoriesArray(Collection $categories, int $level = 0, int $exclude_category_id = null)
    {
        if($exclude_category_id) {
            $categories = $categories->where('id', '!=', $exclude_category_id);
        }

        $array = collect();
        if($categories->count()) {
            foreach($categories as $category) {
                $array[] = (object) [
                    'id' => $category->id,
                    'name' => str_repeat(' - ', $level) . $category->name,
                    'count' => $category->media->count()
                ];

                if($category->children()->count()) {
                    $childrenCategories = $category->children()->orderBy('name', 'asc')->get();

                    if($exclude_category_id) {
                        $childrenCategories = $childrenCategories->where('id', '!=', $exclude_category_id);
                    }

                    $array = $array->merge(self::recursiveCategoriesArray($childrenCategories, ++$level, $exclude_category_id));
                    $level--;
                }
            }
        }

        return $array;
    }

    /**
     * Return Categories with Children as one dimension collection
     * @param Collection $categories
     * @param int $level
     * @param string $status
     * @return Collection
     */
    public static function recursiveCategories(Collection $categories, int $level = 0, string $status = 'published')
    {
        $collection = collect();
        if($categories->count()) {
            foreach ($categories as $category) {

                $collection->push($category);

                if($category->children()->where('status', $status)->count()) {
                    $childrenCategories = $category->children()->where('status', $status)->get();

                    $children = self::recursiveCategories($childrenCategories, $level++, $status);

                    if($children->count()) {
                        foreach($children as $child) {
                            $child->name = str_repeat('—', $level) . ' ' . $child->name;
                            $collection->push($child);
                        }
                    }
                    $level--;
                }
            }
        }

        return $collection;
    }
}
