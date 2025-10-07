<?php

namespace Leobsst\LaravelCmsCore\Models\Features\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Leobsst\LaravelCmsCore\Enums\FieldTypeEnum;

/**
 * @property string $name
 * @property FieldTypeEnum $type
 * @property mixed $value
 * @property mixed $default_value
 * @property int $page_id
 */
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
