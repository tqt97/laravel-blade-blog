<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    /** @use HasFactory<\Database\Factories\ImageFactory> */
    use HasFactory;

    protected $fillable = ['imageable_id', 'imageable_type', 'path', 'alt_text'];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
