<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public $table = 'users';

    protected $hidden = [
        'remember_token', 'two_factor_code',
        'password',
    ];

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
        'two_factor_expires_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'two_factor',
        'two_factor_code',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
        'two_factor_expires_at',
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function generateTwoFactorCode(): void
    {
        $this->timestamps = false;
        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(15)->format(config('panel.date_format').' '.config('panel.time_format'));
        $this->save();
    }

    public function resetTwoFactorCode(): void
    {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::created(function (self $user) {
            $registrationRole = config('panel.registration_default_role');
            if (! $user->roles()->get()->contains($registrationRole)) {
                $user->roles()->attach($registrationRole);
            }
        });
    }

    public function getEmailVerifiedAtAttribute($value): ?string
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format').' '.config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value): void
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format').' '.config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input): void
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function getTwoFactorExpiresAtAttribute($value): ?string
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format').' '.config('panel.time_format')) : null;
    }

    public function setTwoFactorExpiresAtAttribute($value): void
    {
        $this->attributes['two_factor_expires_at'] = $value ? Carbon::createFromFormat(config('panel.date_format').' '.config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }
    public function getUserAvatarAttribute(): ?string
    {
        if ($this->avatar) {
            return asset($this->avatar);
        }

        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
    }
}
