<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
        'status',
        'is_system',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'permissions' => 'array',
            'status' => 'integer',
            'is_system' => 'boolean',
        ];
    }

    /**
     * Code-level relationship with Users.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * Code-level relationship with Permissions via role_permissions pivot table.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    /**
     * Scope a query to only include active roles.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to filter roles by status.
     */
    public function scopeByStatus(Builder $query, int $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to search roles by name or slug.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('slug', 'like', "%{$term}%");
        });
    }
}
