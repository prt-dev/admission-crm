<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicSession extends Model
{
    use HasFactory;

    // Status constants
    public const STATUS_UPCOMING = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_COMPLETED = 3;
    public const STATUS_ARCHIVED = 4;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'academic_sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'start_date',
        'end_date',
        'is_current',
        'status',
        'description',
        'created_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
            'status' => 'integer',
            'created_by' => 'integer',
        ];
    }

    /**
     * Code-level relationship with Batches belonging to this session.
     */
    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class, 'academic_session_id');
    }

    /**
     * Code-level relationship with Admissions enrolled in this session.
     */
    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class, 'academic_session_id');
    }

    /**
     * Code-level relationship with Creator User.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include the current active academic session.
     */
    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('is_current', true);
    }

    /**
     * Scope a query to only include active/ongoing sessions.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus(Builder $query, int $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to search sessions by name, code, or description.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('code', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    /**
     * Human-readable label for session status.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_UPCOMING => 'Upcoming',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_ARCHIVED => 'Archived',
            default => 'Unknown',
        };
    }

    /**
     * Format date span (e.g. "Apr 2025 - Mar 2026").
     */
    public function getFormattedDateRangeAttribute(): string
    {
        $start = $this->start_date ? $this->start_date->format('M Y') : '';
        $end = $this->end_date ? $this->end_date->format('M Y') : '';

        return $start && $end ? "{$start} - {$end}" : $this->name;
    }
}
