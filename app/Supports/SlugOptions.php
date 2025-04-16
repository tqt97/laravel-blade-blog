<?php

namespace App\Supports;

class SlugOptions
{
    public string $from = 'title';

    public string $to = 'slug';

    public string $separator = '-';

    public bool $unique = true;

    public static function create(): self
    {
        return new self;
    }

    public function generateSlugsFrom(string $field): self
    {
        $this->from = $field;

        return $this;
    }

    public function saveSlugsTo(string $field): self
    {
        $this->to = $field;

        return $this;
    }

    public function useSeparator(string $separator): self
    {
        $this->separator = $separator;

        return $this;
    }

    public function doNotGenerateUniqueSlugs(): self
    {
        $this->unique = false;

        return $this;
    }
}
