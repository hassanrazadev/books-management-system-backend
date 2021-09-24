<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Author extends Model implements HasMedia {
    use HasFactory, HasMediaTrait;

    protected $fillable = ['name', 'date_of_birth', 'date_of_death', 'country'];
    protected $appends = ['image'];
    /**
     * Define collection for media
     */
    public function registerMediaCollections() {
        $this->addMediaCollection('image')
            ->useFallbackUrl('assets/img/default-user.png')
            ->singleFile();
    }

    /**
     * @param string $collectionName
     * @return string
     */
    public function getFallbackMediaUrl(string $collectionName = 'image'): string {
        return asset('assets/img/default-user.png');
    }

    /**
     * @return string
     */
    public function getImageAttribute(): string {
        return asset($this->getFirstMediaUrl('image'));

    }

    /**
     * @return HasMany
     */
    public function books(): HasMany {
        return $this->hasMany(Book::class);
    }

    /**
     * @param $dateOfBirth
     */
    public function setDateOfBirthAttribute($dateOfBirth) {
        $this->attributes['date_of_birth'] = Carbon::parse($dateOfBirth)->format('Y-m-d');
    }

    /**
     * @param $dateOfDeath
     */
    public function setDateOfDeathAttribute($dateOfDeath) {
        $this->attributes['date_of_death'] = Carbon::parse($dateOfDeath)->format('Y-m-d');
    }

    /**
     * Boot method
     */
    protected static function boot() {
        parent::boot();
        self::saved(function (Author $author) {
            if (request()->hasFile('image')) {
                $author->addMediaFromRequest('image')->toMediaCollection('image');
            }
        });
    }
}
