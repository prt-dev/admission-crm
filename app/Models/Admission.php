<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'admission_number',
        'registration_number',
        'user_id',
        'lead_id',
        'course_id',
        'batch_id',
        'academic_session_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'alternate_phone',
        'dob',
        'gender',
        'guardian_name',
        'guardian_phone',
        'address',
        'city',
        'state',
        'pincode',
        'qualification',
        'admission_date',
        'course_fee',
        'discount_amount',
        'final_fee',
        'paid_amount',
        'due_amount',
        'payment_status',
        'status',
        'remarks',
        'admitted_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'lead_id' => 'integer',
            'course_id' => 'integer',
            'batch_id' => 'integer',
            'academic_session_id' => 'integer',
            'admitted_by' => 'integer',
            'payment_status' => 'integer',
            'status' => 'integer',
            'dob' => 'date',
            'admission_date' => 'date',
            'course_fee' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'final_fee' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_amount' => 'decimal:2',
        ];
    }

    /**
     * Code-level relationship with User (student account).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Code-level relationship with Lead.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    /**
     * Code-level relationship with Course.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Code-level relationship with Batch.
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    /**
     * Code-level relationship with Academic Session.
     */
    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class, 'academic_session_id');
    }

    /**
     * Code-level relationship with Admitting counselor User.
     */
    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admitted_by');
    }

    /**
     * Scope a query to filter admissions by status.
     */
    public function scopeByStatus(Builder $query, int $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter admissions by payment status.
     */
    public function scopeByPaymentStatus(Builder $query, int $paymentStatus): Builder
    {
        return $query->where('payment_status', $paymentStatus);
    }

    /**
     * Scope a query to search admissions by student name, email, phone, admission/registration number.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('admission_number', 'like', "%{$term}%")
              ->orWhere('registration_number', 'like', "%{$term}%")
              ->orWhere('first_name', 'like', "%{$term}%")
              ->orWhere('last_name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('city', 'like', "%{$term}%");
        });
    }

    /**
     * Code-level relationship with Attendances.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'admission_id');
    }

    /**
     * Get the student's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
