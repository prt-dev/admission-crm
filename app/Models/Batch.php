<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'course_id',
        'start_date',
        'end_date',
        'timing',
        'capacity',
        'status',
        'instructor_id',
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
            'course_id' => 'integer',
            'capacity' => 'integer',
            'status' => 'integer',
            'instructor_id' => 'integer',
            'created_by' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
     * Code-level relationship with Course.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Code-level relationship with Instructor User.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Code-level relationship with Creator User.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Code-level relationship with Admissions.
     */
    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class, 'batch_id');
    }

    /**
     * Scope a query to filter batches by status.
     */
    public function scopeByStatus(Builder $query, int $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter batches by course.
     */
    public function scopeByCourse(Builder $query, int $courseId): Builder
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Scope a query to search batches by name or code.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('code', 'like', "%{$term}%");
        });
    }
}
