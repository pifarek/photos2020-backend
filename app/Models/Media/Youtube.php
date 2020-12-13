<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;

class Youtube extends Model
{
    protected $table = 'media_youtube';
    public $timestamps = false;

    protected $fillable = ['code', 'filename'];

    /**
     * Get the video code from URL
     * @param string $link
     * @return mixed|string
     */
    public static function getCode(string $link)
    {
        $rx = '~^(?:https?://)?(?:www[.])?(?:youtube[.]com/watch[?]v=|youtu[.]be/)([^&]{11})~x';

        preg_match($rx, $link, $matches);

        if(sizeof($matches)){
            return $matches[1];
        }

        return '';
    }
}
