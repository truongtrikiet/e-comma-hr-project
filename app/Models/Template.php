<?php

namespace App\Models;

use App\Enum\TemplateObjectType;
use App\Enum\TemplateStatus;
use App\Enum\TemplateType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Template
 *
 */
class Template extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const TEMPLATE_BACKGROUND_IMAGE_COLLECTION = 'template_background_image';

    protected $fillable = [
        'name',
        'subject',
        'content',
        'type',
        'object_type',
        'status',
        'send_time',
        'send_date',
    ];

    protected $casts = [
        'send_time' => 'datetime',
        'send_date' => 'datetime',
        'type' => TemplateType::class,
        'object_type' => TemplateObjectType::class,
        'status' => TemplateStatus::class,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['background_image'];

    protected $with = ['media'];

    /**
     * Define a many-to-many relationship with the EmailAttribute model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function emailAttributes(): BelongsToMany
    {
        return $this->belongsToMany(
            EmailAttribute::class,
            'template_email_attributes',
            'template_id',
            'email_attribute_id'
        );
    }

    /**
     * Get background image of template.
     */
    public function backgroundImage(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getFirstMedia(self::TEMPLATE_BACKGROUND_IMAGE_COLLECTION) ?
                Storage::url($this->getFirstMedia(self::TEMPLATE_BACKGROUND_IMAGE_COLLECTION)->getPathRelativeToRoot())
                : '',
        );
    }

    public function media(): MorphMany
    {
        return $this->morphMany(config('media-library.media_model'), 'model');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::TEMPLATE_BACKGROUND_IMAGE_COLLECTION)->singleFile();
    }
}