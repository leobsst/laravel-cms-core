<?php

namespace Leobsst\LaravelCmsCore\Models\Features\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Leobsst\LaravelCmsCore\Enums\FieldTypeEnum;

class PageOption extends Model
{
    protected $fillable = [
        'page_id',
        'name',
        'value',
        'default_value',
        'type',
    ];

    protected $casts = [
        'type' => FieldTypeEnum::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
