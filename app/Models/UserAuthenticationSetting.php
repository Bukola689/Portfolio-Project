<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuthenticationSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'authenticator_secret_key',
        'updated_at',
    ];

    protected $casts = [
        'has_2fa_enabled' => 'boolean',
        'enabled_2fa_methods' => 'array',
    ];

    public const TWO_FACTOR_AUTH_METHODS = [
        'EMAIL' => 'email',
        'GOOGLE_AUTH' => 'google_auth',
    ];

    /**
     * Default authentication setting for users.
     */
    public const DEFAULT_2FA_METHODS = [
        self::TWO_FACTOR_AUTH_METHODS['EMAIL'],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Encrypt the user's google_2fa secret.
     *
     * @param string $value
     *
     * @return string
     */
    public function setAuthenticatorSecretKeyAttribute($value)
    {
        $this->attributes['authenticator_secret_key'] = encrypt($value);
    }

    /**
     * Decrypt the user's google_2fa secret.
     *
     * @param string $value
     *
     * @return string
     */
    public function getAuthenticatorSecretKeyAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }
}
