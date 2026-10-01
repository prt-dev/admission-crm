<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    // Type constants
    public const TYPE_THEORY = 'T';
    public const TYPE_PRACTICAL = 'P';
    public const TYPE_OJT = 'O';

    // Status constants
    public const STATUS_PRESENT = 1;
    public const STATUS_ABSENT = 2;
    public const STATUS_LATE = 3;
    public const STATUS_HALF_DAY = 4;
    public const STATUS_LEAVE = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'admission_id',
        'batch_id',
        'course_id',
        'date',
        'duration',
        'type',
        'status',
        'topic_covered',
        'remarks',
        'marked_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'admission_id' => 'integer',
            'batch_id' => 'integer',
            'course_id' => 'integer',
            'marked_by' => 'integer',
            'status' => 'integer',
            'duration' => 'decimal:2',
            'date' => 'date',
        ];
    }

    /**
     * Code-level relationship with Admission (Student).
     */
    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class, 'admission_id');
    }

    /**
     * Code-level relationship with Batch.
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    /**
     * Code-level relationship with Course.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Code-level relationship with Marker User (Trainer/Admin).
     */
    public function marker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    /**
     * Scope a query to filter attendances by batch.
     */
    public function scopeByBatch(Builder $query, int $batchId): Builder
    {
        return $query->where('batch_id', $batchId);
    }

    /**
     * Scope a query to filter attendances by student admission.
     */
    public function scopeByAdmission(Builder $query, int $admissionId): Builder
    {
        return $query->where('admission_id', $admissionId);
    }

    /**
     * Scope a query to filter attendances by course.
     */
    public function scopeByCourse(Builder $query, int $courseId): Builder
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Scope a query to filter attendances by session type (T, P, O).
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', strtoupper($type));
    }

    /**
     * Scope a query to filter attendances by status.
     */
    public function scopeByStatus(Builder $query, int $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter attendances by date.
     */
    public function scopeByDate(Builder $query, string $date): Builder
    {
        return $query->where('date', $date);
    }

    /**
     * Scope a query to filter attendances by date range.
     */
    public function scopeByDateRange(Builder $query, ?string $startDate, ?string $endDate): Builder
    {
        if (!empty($startDate) && !empty($endDate)) {
            return $query->whereBetween('date', [$startDate, $endDate]);
        }

        if (!empty($startDate)) {
            return $query->where('date', '>=', $startDate);
        }

        if (!empty($endDate)) {
            return $query->where('date', '<=', $endDate);
        }

        return $query;
    }

    /**
     * Scope a query to search attendances by topic or remarks.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('topic_covered', 'like', "%{$term}%")
              ->orWhere('remarks', 'like', "%{$term}%");
        });
    }

    /**
     * Human-readable label for attendance type (T, P, O).
     */
    public function getTypeLabelAttribute(): string
    {
        return match (strtoupper($this->type)) {
            self::TYPE_THEORY => 'Theory',
            self::TYPE_PRACTICAL => 'Practical',
            self::TYPE_OJT => 'OJT (On-the-Job Training)',
            default => $this->type ?? 'Theory',
        };
    }

    /**
     * Human-readable label for attendance status.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PRESENT => 'Present',
            self::STATUS_ABSENT => 'Absent',
            self::STATUS_LATE => 'Late',
            self::STATUS_HALF_DAY => 'Half Day',
            self::STATUS_LEAVE => 'Leave',
            default => 'Unknown',
        };
    }
}
