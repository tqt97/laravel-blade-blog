<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug', 'parent_id'];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Scope để lấy các danh mục cha (không có parent)
    public function scopeParent(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    // Scope để lấy các danh mục con của 1 danh mục cha
    public function scopeChildrenOf(Builder $query, int $parentId): Builder
    {
        return $query->where('parent_id', $parentId);
    }
}
