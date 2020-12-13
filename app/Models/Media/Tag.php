<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';
    public $timestamps = false;

    public function media()
    {
        return $this->belongsToMany(Media::class);
    }
}
