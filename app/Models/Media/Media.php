<?php

namespace App\Models\Media;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';
    protected $dates = ['created_at', 'updated_at', 'taken_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function photo()
    {
        return $this->hasOne(Photo::class, 'media_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function youtube()
    {
        return $this->hasOne(Youtube::class, 'media_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function tagsString()
    {
        $tags = $this->tags();

        if($tags) {
            return implode(';', $tags->pluck('tags.id')->toArray());
        }

        return '';
    }

    /**
     * Display media thumbnail
     */
    public function thumbnail()
    {
        return $this->filename;
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    /**
     * Delete selected Media
     * @return bool|void|null
     * @throws \Exception
     */
    public function delete()
    {
        if($this->type === 'photo') {
            \File::delete('upload/images/f/' . $this->filename,
                'upload/images/m/' . $this->filename,
                'upload/images/s/' . $this->filename
            );
        }

        if($this->type === 'youtube') {
            \File::delete('upload/images/f/' . $this->filename,
                'upload/images/m/' . $this->filename,
                'upload/images/s/' . $this->filename
            );
        }

        parent::delete();
    }

    /**
     * Generate random numbers
     */
    public static function randomize()
    {
        $media = Media::get();

        $count = 0;
        foreach($media->shuffle() as $item) {
            $item->order_random = $count;
            $item->save();

            $count++;
        }
    }
}
