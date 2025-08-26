<?php

namespace Leobsst\LaravelCmsCore\Models\Features\Pages;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class PageStat
 *
 * @property int $id
 * @property int $page_id
 * @property string $ip
 * @property string $country
 * @property string $city
 * @property Carbon $created_at
 * @property Carbon $updated_at
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
