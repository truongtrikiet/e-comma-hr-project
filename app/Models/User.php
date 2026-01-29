<?php

namespace App\Models;

use App\Enum\UserStatus;
use App\Traits\Contractable;
use App\Traits\HasOneAddress;
use App\Traits\EmailNotifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Vite;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\User
 *
 */

class User extends Authenticatable implements HasMedia, Auditable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, InteractsWithMedia, HasOneAddress;
    use Contractable, EmailNotifiable;
    use AuditingTrait;

    const USER_AVATAR_COLLECTION = 'user_avatar';

    const USER_AVATAR_RESIZE_NAME = 'avatar_resize';

    const BACKGROUND_COLLECTION = 'background';

    const CONVERSION_SIZE = [
        'width' => '650',
        'height' => '415',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'status',
        'password',
        'login_at',
        'email_verified_at',
        'remember_token',
        'school_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Auditable events.
     *
     * @var array
     */
    protected $auditEvents = [
        'sync',
        'attach',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'login_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
        ];
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['avatar_url'];

    protected $with = ['media'];

    /**
     * Get the user avatar url
     *
     * @return string
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!$this->relationLoaded('media')) {
            $this->load('media');
        }

        $mediaUrl = $this->getFirstMediaUrl(self::USER_AVATAR_COLLECTION);

        return $mediaUrl ?: Vite::asset('resources/images/profile/profile.png');
    }

    /**
     * Get the user background url.
     *
     * @return Attribute
     */

    public function background(): Attribute
    {
        return Attribute::make(
            fn($value) => $this->getFirstMediaUrl(self::BACKGROUND_COLLECTION) ?: Vite::asset('resources/images/no-image.jpg')
        );
    }

    /**
     * Boot the model and apply the global scope.
     */
    protected static function booted()
    {
        static::addGlobalScope('school', function (Builder $builder) {
            $sessionSchoolName = session('school_name');
            $envSchoolName = config('subdomain.system_main');

            if (is_null($sessionSchoolName) || is_null($envSchoolName)) {
                return;
            }

            if ($sessionSchoolName !== $envSchoolName) {
                $builder->where('school_id', session('school_id'));
            }
        });
    }

    /**
     * Define a one-to-one relationship with the UserProfile model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userProfile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Define a many-to-many relationship with the School model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
