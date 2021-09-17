<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class User extends Authenticatable implements HasMedia {
    use HasApiTokens, HasFactory, Notifiable, HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return string
     */
    public function getAvatarAttribute(): string {
        return $this->getFirstMediaUrl('avatar');
    }

    /**
     * Hash/bcrypt password
     * @param $password
     */
    public function setPasswordAttribute($password) {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Define collection for media
     */
    public function registerMediaCollections() {
        $this->addMediaCollection('avatar')->singleFile();
    }

    /**
     * @return HasMany
     */
    public function categories(): HasMany {
        return $this->hasMany(Category::class);
    }

    /**
     * @return HasMany
     */
    public function users(): HasMany {
        return $this->hasMany(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * Boot method of User
     */
    protected static function boot() {
        parent::boot();
        self::created(function (User $user) {
            if (request()->has('avatar')) {
                $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            }
        });
    }
}
