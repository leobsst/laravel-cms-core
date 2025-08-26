<?php

namespace Leobsst\LaravelCmsCore\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Feature
 *
 * @property int $id
 * @property string $name
 * @property string $scope
 * @property bool $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Feature extends Model
{
    protected $fillable = [
        'name',
        'scope',
        'value',
    ];

    protected $hidden = [
        'scope',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'value' => 'boolean',
    ];
}
