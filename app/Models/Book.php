<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Book extends Model implements HasMedia {
    use HasFactory, HasMediaTrait;

    protected $fillable = ['title', 'description', 'published_date', 'author_id'];
    protected $appends = ['image'];

    /**
     * Define collection for media
     */
    public function registerMediaCollections() {
        $this->addMediaCollection('image')->singleFile();
    }

    public function getFallbackMediaUrl(string $collectionName = 'default'): string {
        return asset('assets/img/default-book.png');
    }

    /**
     * @return string
     */
    public function getImageAttribute(): string {
        return asset($this->getFirstMediaUrl('image'));
    }

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo {
        return $this->belongsTo(Auth::class);
    }

    /**
     * Boot Method
     */
    protected static function boot() {
        parent::boot();
        self::saved(function (Book $book) {
            if (request()->hasFile('image')) {
                $book->addMediaFromRequest('image')->toMediaCollection('image');
            }
        });
    }
}
