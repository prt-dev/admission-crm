<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'phone',
        'password',
        'role_id',
        'status',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'status' => 'integer',
            'role_id' => 'integer',
        ];
    }

    /**
     * Code-level relationship with Role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        if (!$this->role) {
            return false;
        }

        // Check if role has wildcard superadmin permission
        $rolePermissions = $this->role->permissions ?? [];
        if (in_array('*', $rolePermissions) || in_array($permissionSlug, $rolePermissions)) {
            return true;
        }

        // Also check role_permissions relation if loaded or queried
        if ($this->role->relationLoaded('permissions')) {
            return $this->role->permissions->contains('slug', $permissionSlug)
                || $this->role->permissions->contains('slug', '*');
        }

        return $this->role->permissions()->where('slug', $permissionSlug)->exists();
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to filter users by role ID.
     */
    public function scopeByRole(Builder $query, int $roleId): Builder
    {
        return $query->where('role_id', $roleId);
    }

    /**
     * Scope a query to filter users by status.
     */
    public function scopeByStatus(Builder $query, int $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to search users by username, name, email, or phone.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('username', 'like', "%{$term}%")
              ->orWhere('name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    /**
     * Record last login timestamp.
     */
    public function touchLastLogin(?DateTimeInterface $dateTime = null): bool
    {
        $this->last_login_at = $dateTime ?? now();
        return $this->save();
    }
}
