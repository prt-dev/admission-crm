<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'academic_session_id',
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
            'academic_session_id' => 'integer',
            'capacity' => 'integer',
            'status' => 'integer',
            'instructor_id' => 'integer',
            'created_by' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
     * Code-level relationship with Academic Session.
     */
    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class, 'academic_session_id');
    }

    /**
     * Code-level relationship with Courses (Many-to-Many via pivot table).
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'batch_courses', 'batch_id', 'course_id')->withTimestamps();
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
     * Code-level relationship with Attendances.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'batch_id');
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
        return $query->whereHas('courses', function (Builder $q) use ($courseId) {
            $q->where('courses.id', $courseId);
        });
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
