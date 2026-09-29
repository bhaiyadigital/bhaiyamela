<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Content extends Model
{
    protected $table = 'contents';
    protected $fillable = [
        'module',
        'title',
        'slug',
        'prev_slug',
        'short',
        'description',
        'description_1',
        'description_2',
        'description_3',
        'features',
        'extra',
        'url',
        'location',
        'img_path',
        'img_paths',
        'video_path',
        'video_paths',
        'parent_id',
        'start_date',
        'end_date',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'sort_order',
        'status',
        'admin_approved',
        'published_at',
        'trashed_at',
        'scheduled_at',
        'company_id',
        'user_id',
        'parent_id',
        'destination_id',
        'virtual_tour',
    ];
    protected static function booted()
    {
        static::addGlobalScope(new TenantScope);
    }

    protected $casts = [
        'features'     => 'array',
        'extra'        => 'array',
        'img_paths'    => 'array',
        'video_paths'  => 'array',
        'start_date'   => 'datetime',
        'end_date'     => 'datetime',
        'published_at' => 'datetime',
        'trashed_at'   => 'datetime',
        'scheduled_at'   => 'datetime',
    ];

    const STATUS_INACTIVE  = 0;
    const STATUS_ACTIVE    = 1;
    const STATUS_SCHEDULED = 2;
    const STATUS_TRASH     = 3;



    public function parent()
    {
        return $this->belongsTo(Content::class, 'parent_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Content::class, 'parent_id')->where('module', 'category');
    }
    public function destination()
    {
        return $this->belongsTo(Content::class, 'destination_id')->where('module', 'destination');
    }
    // Child records (e.g. gallery photos under an album)
    public function children()
    {
        return $this->hasMany(Content::class, 'parent_id')->orderBy('sort_order');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    // ------------------------------------------------------------------
    // Query scopes
    // ------------------------------------------------------------------

    // Filter by module name
    public function scopeModule(Builder $query, string $module): Builder
    {
        return $query->where('module', $module);
    }

    // Only active records
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('admin_approved', 1);
    }

    // Active + scheduled records that are now past their published_at
    public function scopePublished(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('status', self::STATUS_ACTIVE)
                ->orWhere(function ($q2) {
                    $q2->where('status', self::STATUS_SCHEDULED)
                        ->where('published_at', '<=', now());
                });
        });
    }

    // Exclude trashed records
    public function scopeNotTrashed(Builder $query): Builder
    {
        return $query->where('status', '!=', self::STATUS_TRASH);
    }

    // Only trashed records
    public function scopeTrashed(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_TRASH);
    }

    // Order by sort_order ascending
    public function scopeSorted(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }


    // Returns human-readable status label
    public function getStatusLabelAttribute(): string
    {
        return match ((int) $this->status) {
            self::STATUS_ACTIVE    => 'Active',
            self::STATUS_INACTIVE  => 'Inactive',
            self::STATUS_SCHEDULED => 'Scheduled',
            self::STATUS_TRASH     => 'Trash',
            default                => 'Unknown',
        };
    }

    // Returns first image from img_paths or falls back to img_path
    public function getMainImageAttribute(): ?string
    {
        if (!empty($this->img_paths)) {
            return  $this->img_paths[0] ?? null;
        }
        return $this->img_path;
    }

    // Slug helper — generate unique slug from a given string
    public static function generateSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;

        while (
            static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug)
            ->orWhere('prev_slug', $slug);
    }
    public function setDescription1Attribute($value)
    {
        $this->attributes['description_1'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getDescription1Attribute($value)
    {
        if (is_null($value)) return null;
        $decoded = json_decode($value, true);
        return (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : $value;
    }

    public function setDescription2Attribute($value)
    {
        $this->attributes['description_2'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getDescription2Attribute($value)
    {
        if (is_null($value)) return null;
        $decoded = json_decode($value, true);
        return (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : $value;
    }
    public function getDescriptionStatusAttribute()
    {
        return $this->extra['description_status'] ?? '1'; // Default active
    }
    public function setDescriptionStatusAttribute($value)
    {
        $extra = $this->extra ?? [];
        $extra['description_status'] = $value;
        $this->extra = $extra;
    }

    // ── Description 2 (description_1) Status Accessor & Mutator ──
    public function getDescription1StatusAttribute()
    {
        return $this->extra['description_1_status'] ?? '1';
    }
    public function setDescription1StatusAttribute($value)
    {
        $extra = $this->extra ?? [];
        $extra['description_1_status'] = $value;
        $this->extra = $extra;
    }

    // ── Description 3 (description_2) Status Accessor & Mutator ──
    public function getDescription2StatusAttribute()
    {
        return $this->extra['description_2_status'] ?? '1';
    }
    public function setDescription2StatusAttribute($value)
    {
        $extra = $this->extra ?? [];
        $extra['description_2_status'] = $value;
        $this->extra = $extra;
    }
    public function getDescriptionTitleAttribute()
    {
        return $this->extra['description_title'] ?? 'Overview'; // Default title
    }
    public function setDescriptionTitleAttribute($value)
    {
        $extra = $this->extra ?? [];
        $extra['description_title'] = $value;
        $this->extra = $extra;
    }

    // ── Description 2 (description_1) Title Accessor ──
    public function getDescription1TitleAttribute()
    {
        return $this->extra['description_1_title'] ?? 'Detailed Info';
    }
    public function setDescription1TitleAttribute($value)
    {
        $extra = $this->extra ?? [];
        $extra['description_1_title'] = $value;
        $this->extra = $extra;
    }

    // ── Description 3 (description_2) Title Accessor ──
    public function getDescription2TitleAttribute()
    {
        return $this->extra['description_2_title'] ?? 'Additional Details';
    }
    public function setDescription2TitleAttribute($value)
    {
        $extra = $this->extra ?? [];
        $extra['description_2_title'] = $value;
        $this->extra = $extra;
    }
}
