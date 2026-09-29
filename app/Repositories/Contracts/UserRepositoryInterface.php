<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * Get all users.
     *
     * @param array<int, string> $columns
     * @return Collection<int, User>
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Get paginated users with optional filters.
     *
     * @param int $perPage
     * @param array<int, string> $columns
     * @param array<string, mixed> $filters
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator;

    /**
     * Find a user by primary ID.
     *
     * @param int|string $id
     * @param array<int, string> $columns
     * @return User|null
     */
    public function findById(int|string $id, array $columns = ['*']): ?User;

    /**
     * Find a user by username.
     *
     * @param string $username
     * @param array<int, string> $columns
     * @return User|null
     */
    public function findByUsername(string $username, array $columns = ['*']): ?User;

    /**
     * Find a user by email.
     *
     * @param string $email
     * @param array<int, string> $columns
     * @return User|null
     */
    public function findByEmail(string $email, array $columns = ['*']): ?User;

    /**
     * Create a new user record.
     *
     * @param array<string, mixed> $data
     * @return User
     */
    public function create(array $data): User;

    /**
     * Update an existing user record.
     *
     * @param int|string $id
     * @param array<string, mixed> $data
     * @return User|null
     */
    public function update(int|string $id, array $data): ?User;

    /**
     * Delete a user by ID.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete(int|string $id): bool;

    /**
     * Update user status.
     *
     * @param int|string $id
     * @param int|string $status
     * @return User|null
     */
    public function updateStatus(int|string $id, int|string $status): ?User;

    /**
     * Update last login timestamp for user.
     *
     * @param int|string $id
     * @param \DateTimeInterface|null $dateTime
     * @return User|null
     */
    public function updateLastLogin(int|string $id, ?\DateTimeInterface $dateTime = null): ?User;
}
