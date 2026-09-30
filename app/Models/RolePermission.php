<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermission extends Model
{
    use HasFactory;

    protected $table = 'role_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role_id' => 'integer',
            'permission_id' => 'integer',
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
     * Code-level relationship with Permission.
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
