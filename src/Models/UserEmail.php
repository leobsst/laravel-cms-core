<?php

namespace Leobsst\LaravelCmsCore\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserEmail
 *
 * @property int $id
 * @property int $user_id
 * @property string $email
 * @property User $user
 */
class UserEmail extends Model
{
    use MustVerifyEmail;
    protected $fillable = [
        'user_id',
        'email',
        'email_verified_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
