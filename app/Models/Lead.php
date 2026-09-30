<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'alternate_phone',
        'city',
        'state',
        'source',
        'course_interested',
        'assigned_to',
        'status',
        'notes',
        'next_follow_up_at',
        'converted_at',
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
            'status' => 'integer',
            'assigned_to' => 'integer',
            'created_by' => 'integer',
            'next_follow_up_at' => 'datetime',
            'converted_at' => 'datetime',
        ];
    }

    /**
     * Code-level relationship with assigned User.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Code-level relationship with creating User.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Code-level relationship with Follow-ups.
     */
    public function followUps(): HasMany
    {
        return $this->hasMany(LeadFollowUp::class, 'lead_id')->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to filter leads by status.
     */
    public function scopeByStatus(Builder $query, int $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter leads by assigned user.
     */
    public function scopeAssignedTo(Builder $query, int $userId): Builder
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope a query to filter leads by source.
     */
    public function scopeBySource(Builder $query, string $source): Builder
    {
        return $query->where('source', $source);
    }

    /**
     * Scope a query to search leads by name, email, phone, or city.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('first_name', 'like', "%{$term}%")
              ->orWhere('last_name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('city', 'like', "%{$term}%")
              ->orWhere('course_interested', 'like', "%{$term}%");
        });
    }

    /**
     * Get the full name of the lead.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
