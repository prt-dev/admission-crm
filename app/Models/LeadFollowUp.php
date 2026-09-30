<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadFollowUp extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lead_id',
        'user_id',
        'type',
        'remarks',
        'scheduled_at',
        'completed_at',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'lead_id' => 'integer',
            'user_id' => 'integer',
            'status' => 'integer',
            'scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Code-level relationship with Lead.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    /**
     * Code-level relationship with User (agent).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope a query to only include pending follow-ups.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to only include completed follow-ups.
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 2);
    }
}
