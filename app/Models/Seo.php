<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Seo extends Model
{
    /** @use HasFactory<\Database\Factories\SeoMetaFactory> */
    use HasFactory;

    protected $fillable = ['metaable_id', 'metaable_type', 'meta_title', 'meta_description', 'meta_keywords'];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
