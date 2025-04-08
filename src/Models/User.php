<?php

namespace Leobsst\LaravelCmsCore\Models;

use Leobsst\LaravelCmsCore\Models\Log;
use Filament\Panel;
use Leobsst\LaravelCmsCore\Enums\LogType;
use Leobsst\LaravelCmsCore\Enums\LogStatus;
use Leobsst\LaravelCmsCore\Observers\UserObserver;
use Leobsst\LaravelCmsCore\Services\ClientService;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Leobsst\LaravelCmsCore\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Page
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $avatar
 * @property string $bio
 * @property string $remember_token
 * @property bool $enabled
 * @property \Illuminate\Support\Carbon $email_verified_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|UserEmail[] $emails
 */
#[ObservedBy(classes: [UserObserver::class])]
class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasRoles, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'avatar',
        'bio',
        'enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'enabled' => 'boolean',
    ];

    public function emails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserEmail::class);
    }

    public function logs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Log::class, 'creator_id');
    }

    /**
     * Log an action.
     *
     * @param LogType $type
     * @param string $message
     * @param LogStatus $status
     * @param string|null $data
     * @return void
     */
    public function log(LogType $type, string $message, LogStatus $status, mixed $data = null): void
    {
        $this->logs()->create([
            'type' => $type->value,
            'message' => $message,
            'status' => $status->value,
            'data' => filled($data) ? json_encode($data) : null,
            'ip_address' => ClientService::getIp(),
        ]);
    }

    /**
     * Check if the user has a role to access the panel.
     *
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Must have minimum role of editor to access panel
        return $this->hasRole(['editor']);
    }

    /**
     * Retrieve the user's role.
     *
     * @return string|null
     */
    public function getRoleAttribute(): ?string
    {
        return $this->roles->count() > 0 ? $this->roles->sortBy('id')->first()->name : null;
    }

    /**
     * Send the finalization email.
     *
     * @return void
     */
    public function sendFinalizationEmail(): void
    {
        $token = app('auth.password.broker')->createToken($this);
        $subject = ('Bienvenue sur ' . Setting::get('website_name'));
        self::notify(new ResetPasswordNotification(
            token: $token,
            subject: $subject,
            action: 'Définir mon mot de passe',
            l1: 'Votre compte vient d\'être créé avec succès.',
            l2: 'Pour vous connecter, veuillez cliquer sur le bouton ci-dessous afin de définir votre mot de passe.'
        ));
    }
}
