<?php

namespace Leobsst\LaravelCmsCore\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
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
 * @property ?string $uuid
 * @property string $name
 * @property ?string $first_name
 * @property ?string $username
 * @property bool $username_visible
 * @property string $email
 * @property Carbon $email_verified_at
 * @property ?string $phone
 * @property string $password
 * @property ?string $remember_token
 * @property ?string $two_fa_secret
 * @property bool $two_fa_enabled
 * @property ?string $avatar
 * @property bool $avatar_gravatar
 * @property string $bio
 * @property ?string $facebook
 * @property ?string $twitter
 * @property ?string $instagram
 * @property ?string $tiktok
 * @property ?string $youtube
 * @property ?string $pinterest
 * @property ?string $linkedin
 * @property ?string $github
 * @property ?string $website
 * @property ?string $extra_data
 * @property bool $enabled
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|UserEmail[] $emails
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
        'uuid',
        'name',
        'first_name',
        'username',
        'username_visible',
        'email',
        'email_verified_at',
        'phone',
        'password',
        'remember_token',
        'two_fa_secret',
        'two_fa_enabled',
        'avatar',
        'avatar_gravatar',
        'bio',
        'facebook',
        'twitter',
        'instagram',
        'tiktok',
        'youtube',
        'pinterest',
        'linkedin',
        'github',
        'website',
        'extra_data',
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
        'two_fa_secret',
        'two_fa_enabled',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'username_visible' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_fa_enabled' => 'boolean',
        'avatar_gravatar' => 'boolean',
        'extra_data' => 'array',
        'enabled' => 'boolean',
    ];

    public function emails(): HasMany
    {
        return $this->hasMany(UserEmail::class);
    }

    public function logs(): HasMany
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
