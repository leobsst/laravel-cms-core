<?php

namespace Leobsst\LaravelCmsCore\Models;

use Leobsst\LaravelCmsCore\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PageStat
 *
 * @property int $id
 * @property int $page_id
 * @property string $ip
 * @property string $country
 * @property string $city
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property Page $page
 */
class PageStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'ip',
        'country',
        'city',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
