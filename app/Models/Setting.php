<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enum\SettingStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Vite;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Setting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const BANNER_URL_COLLECTION = 'banner_url';
    const KEY_JSON_COLLECTION = 'key_json';
    const IMAGE_COLLECTION = 'image';

    protected $fillable = [
        'key',
        'value',
        'status',
    ];

    protected $table = 'settings';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_enabled' => 'boolean',
        'status' => SettingStatus::class,
    ];

    protected $with = ['media'];

    /**
     * Is enabled or not
     */
    protected function isEnabled(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->status == SettingStatus::ENABLED
        );
    }

    /**
     * Get the general setting banner URL.
     *
     * @return string
     */
    public function getBannerUrlAttribute(): string
    {
        if (!$this->relationLoaded('media')) {
            return Vite::asset('resources/images/auth-cover.svg');
        }

        $mediaUrl = $this->getFirstMediaUrl(self::BANNER_URL_COLLECTION);

        return $mediaUrl ?: Vite::asset('resources/images/auth-cover.svg');
    }

    /**
     * Get the image URL.
     *
     * @return string
     */
    public function image(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->getFirstMediaUrl(self::IMAGE_COLLECTION) ?: '',
        );
    }
}
