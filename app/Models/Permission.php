<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
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
        'module',
        'description',
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
            'status' => 'integer',
        ];
    }

    /**
     * Code-level relationship with Roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id');
    }

    /**
     * Scope a query to only include active permissions.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to filter permissions by module.
     */
    public function scopeByModule(Builder $query, string $module): Builder
    {
        return $query->where('module', $module);
    }

    /**
     * Scope a query to search permissions by name or slug.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('slug', 'like', "%{$term}%")
              ->orWhere('module', 'like', "%{$term}%");
        });
    }
}
