<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'media_photos';
    public $timestamps = false;

    protected $fillable = ['filename'];
}
