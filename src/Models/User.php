<?php

namespace Leobsst\LaravelCmsCore\Models;

use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\Email\Contracts\HasEmailAuthentication;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Passport\HasApiTokens;
use Leobsst\LaravelCmsCore\Enums\LogStatus;
use Leobsst\LaravelCmsCore\Enums\LogType;
use Leobsst\LaravelCmsCore\Notifications\ResetPasswordNotification;
use Leobsst\LaravelCmsCore\Observers\UserObserver;
use Leobsst\LaravelCmsCore\Services\ClientService;
use Spatie\Permission\Traits\HasRoles;

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
 * @property array $extra_data
 * @property bool $enabled
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|UserEmail[] $emails
 */
#[ObservedBy(classes: [UserObserver::class])]
class User extends Authenticatable implements FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery, HasEmailAuthentication, MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;

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
        'app_authentication_secret',
        'app_authentication_recovery_codes',
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
        'app_authentication_secret' => 'encrypted',
        'app_authentication_recovery_codes' => 'encrypted:array',
        'has_email_authentication' => 'boolean',
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
     * @param  string|null  $data
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
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Must have minimum role of editor to access panel
        return $this->hasRole(['editor']);
    }

    /**
     * Determine if the user has verified their email address.
     */
    public function hasVerifiedEmail(): bool
    {
        return filled($this->email_verified_at);
    }

    /**
     * Mark the given user's email as verified.
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);
    }

    /**
     * Get the email address that should be used for verification.
     */
    public function getEmailForVerification(): string
    {
        return $this->email;
    }

    public function getAppAuthenticationSecret(): ?string
    {
        // This method should return the user's saved app authentication secret.

        return $this->app_authentication_secret;
    }

    public function saveAppAuthenticationSecret(?string $secret): void
    {
        // This method should save the user's app authentication secret.

        $this->app_authentication_secret = $secret;
        $this->save();
    }

    public function getAppAuthenticationHolderName(): string
    {
        // In a user's authentication app, each account can be represented by a "holder name".
        // If the user has multiple accounts in your app, it might be a good idea to use
        // their email address as then they are still uniquely identifiable.

        return $this->email;
    }

    /**
     * @return ?array<string>
     */
    public function getAppAuthenticationRecoveryCodes(): ?array
    {
        // This method should return the user's saved app authentication recovery codes.

        return $this->app_authentication_recovery_codes;
    }

    /**
     * @param  array<string> | null  $codes
     */
    public function saveAppAuthenticationRecoveryCodes(?array $codes): void
    {
        // This method should save the user's app authentication recovery codes.

        $this->app_authentication_recovery_codes = $codes;
        $this->save();
    }

    public function hasEmailAuthentication(): bool
    {
        // This method should return true if the user has enabled email authentication.

        return $this->has_email_authentication;
    }

    public function toggleEmailAuthentication(bool $condition): void
    {
        // This method should save whether or not the user has enabled email authentication.

        $this->has_email_authentication = $condition;
        $this->save();
    }

    /**
     * Retrieve the user's role.
     */
    public function getRoleAttribute(): ?string
    {
        return $this->roles->count() > 0 ? $this->roles->sortBy('id')->first()->name : null;
    }

    /**
     * Send the finalization email.
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
