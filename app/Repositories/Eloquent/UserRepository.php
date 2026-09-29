<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use DateTimeInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param User $model
     */
    public function __construct(
        protected User $model
    ) {}

    /**
     * Get all users using Eloquent ORM.
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    /**
     * Get paginated users with optional filters using Eloquent ORM scopes and methods.
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        return $this->model
            ->select($columns)
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->search($filters['search']);
            })
            ->when(isset($filters['role_id']) && $filters['role_id'] !== '', function ($query) use ($filters) {
                $query->byRole((int) $filters['role_id']);
            })
            ->when(isset($filters['status']) && $filters['status'] !== '', function ($query) use ($filters) {
                $query->byStatus((int) $filters['status']);
            })
            ->orderBy(
                $filters['sort_by'] ?? 'created_at',
                strtolower($filters['sort_order'] ?? 'desc') === 'asc' ? 'asc' : 'desc'
            )
            ->paginate($perPage);
    }

    /**
     * Find a user by primary ID using Eloquent find().
     */
    public function findById(int|string $id, array $columns = ['*']): ?User
    {
        return $this->model->find($id, $columns);
    }

    /**
     * Find a user by username using Eloquent.
     */
    public function findByUsername(string $username, array $columns = ['*']): ?User
    {
        return $this->model->where('username', $username)->first($columns);
    }

    /**
     * Find a user by email using Eloquent.
     */
    public function findByEmail(string $email, array $columns = ['*']): ?User
    {
        return $this->model->where('email', $email)->first($columns);
    }

    /**
     * Create a new user using Eloquent create().
     */
    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing user using Eloquent model instance.
     */
    public function update(int|string $id, array $data): ?User
    {
        $user = $this->findById($id);

        if (!$user) {
            return null;
        }

        $user->fill($data);
        $user->save();

        return $user;
    }

    /**
     * Delete a user by ID using Eloquent delete().
     */
    public function delete(int|string $id): bool
    {
        $user = $this->findById($id);

        if (!$user) {
            return false;
        }

        return (bool) $user->delete();
    }

    /**
     * Update user status using Eloquent model update.
     */
    public function updateStatus(int|string $id, int|string $status): ?User
    {
        $user = $this->findById($id);

        if (!$user) {
            return null;
        }

        $user->status = (int) $status;
        $user->save();

        return $user;
    }

    /**
     * Update last login timestamp using Eloquent model method.
     */
    public function updateLastLogin(int|string $id, ?DateTimeInterface $dateTime = null): ?User
    {
        $user = $this->findById($id);

        if (!$user) {
            return null;
        }

        $user->touchLastLogin($dateTime);

        return $user;
    }
}
