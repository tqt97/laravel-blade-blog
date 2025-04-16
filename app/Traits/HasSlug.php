<?php

namespace App\Traits;

use App\Supports\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            $model->generateSlug();
        });

        static::updating(function (Model $model) {
            // Nếu không sửa slug thủ công, sẽ tự cập nhật
            if (! $model->isDirty($model->getSlugOptions()->to)) {
                $model->generateSlug();
            }
        });
    }

    // protected function handleSlug(): void
    // {
    //     $options = $this->getSlugOptions();

    //     $sourceField = $options->from;
    //     $targetField = $options->to;
    //     $separator = $options->separator ?? '-';
    //     $generateUnique = $options->unique;

    //     if (empty($this->{$targetField}) && !empty($this->{$sourceField})) {
    //         $baseSlug = Str::slug($this->{$sourceField}, $separator);
    //         $this->{$targetField} = $generateUnique
    //             ? $this->generateUniqueSlug($baseSlug, $targetField)
    //             : $baseSlug;
    //     }
    // }

    public function generateSlug(): void
    {
        $options = $this->getSlugOptions();

        // Ưu tiên slug người dùng nhập
        // $slug = $this->{$options->to} ?: Str::slug($this->{$options->from}, $options->separator);
        $userSlug = $this->{$options->to};
        $slug = $userSlug
            ? Str::slug($userSlug, $options->separator)
            : Str::slug($this->{$options->from}, $options->separator);

        if ($options->unique && empty($this->{$options->to})) {
            $slug = $this->makeSlugUnique($slug, $options);
        }

        $this->{$options->to} = $slug;
    }

    // protected function generateUniqueSlug(string $baseSlug, string $column): string
    // {
    //     $slug = $baseSlug;
    //     $i = 1;

    //     while (static::where($column, $slug)->exists()) {
    //         $slug = "{$baseSlug}-{$i}";
    //         $i++;
    //     }

    //     return $slug;
    // }

    // public function getSlugOptions(): SlugOptions
    // {
    //     return SlugOptions::create(); // default (có thể override trong model)
    // }

    protected function makeSlugUnique(string $baseSlug, SlugOptions $options): string
    {
        $slug = $baseSlug;
        $i = 1;

        $query = static::query()->where($options->to, $slug);
        if ($this->exists) {
            $query->whereKeyNot($this->getKey());
        }

        while ($query->exists()) {
            $slug = $baseSlug.$options->separator.$i++;
            $query = static::query()->where($options->to, $slug);
            if ($this->exists) {
                $query->whereKeyNot($this->getKey());
            }
        }

        return $slug;
    }

    abstract public function getSlugOptions(): SlugOptions;
}
