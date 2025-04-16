<?php

namespace App\Models;

use App\Enums\PostStatus;
use App\Supports\SlugOptions;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    use HasSlug;
    use SoftDeletes;

    protected $fillable = ['user_id', 'category_id', 'title', 'slug', 'excerpt', 'content', 'status', 'published_at'];

    protected $casts = [
        'status' => PostStatus::class,
        'published_at' => 'datetime',
    ];

    // Định nghĩa global scope trong phương thức booted()
    protected static function booted(): void
    {
        static::addGlobalScope('published', function (Builder $builder) {
            $builder->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now());
        });
    }

    // Nếu muốn lấy tất cả cả bài viết, bao gồm bài draft, có thể loại bỏ global scope như sau:
    // Post::withoutGlobalScope('published')->get();

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->useSeparator('-');
        // ->doNotGenerateUniqueSlugs(); // hoặc bỏ dòng này nếu muốn unique
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // Scope để lấy bài đã publish
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    // Scope để lấy bài nháp (draft)
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    // Scope lọc theo user (tác giả)
    public function scopeByAuthor(Builder $query, $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    // Scope lọc theo category
    public function scopeByCategory(Builder $query, $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    // Scope sắp xếp mới nhất
    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->attributes['status']);
    }

    public function getPublishedAtAttribute(): string
    {
        return $this->attributes['published_at'] ? $this->attributes['published_at']->format('d/m/Y') : null;
    }
}
