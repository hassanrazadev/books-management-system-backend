<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Category extends Model implements HasMedia {
    use HasFactory, HasMediaTrait;

    protected $fillable = ['name', 'category_id', 'user_id'];

    protected $appends = ['image'];

    /**
     * Define collection for media
     */
    public function registerMediaCollections() {
        $this->addMediaCollection('image')
        ->singleFile();
    }

    /**
     * @return string
     */
    public function getImageAttribute(): string {
        return $this->getFirstMedia('image')->getFullUrl();
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany
     */
    public function categories(): HasMany {
        return $this->hasMany(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany
     */
    public function books(): BelongsToMany {
        return $this->belongsToMany(Book::class);
    }

    /**
     * Boot method
     */
    protected static function boot() {
        parent::boot();
        self::saved(function (Category $category) {
            if (request()->hasFile('image')) {
                $category->addMediaFromRequest('image')->toMediaCollection('image');
            }
        });
    }
}
