<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * List all users.
     *
     * @param array<int, string> $columns
     * @return Collection<int, User>
     */
    public function getAllUsers(array $columns = ['*']): Collection
    {
        return $this->userRepository->all($columns);
    }

    /**
     * Get paginated users with filtering.
     *
     * @param int $perPage
     * @param array<int, string> $columns
     * @param array<string, mixed> $filters
     * @return LengthAwarePaginator
     */
    public function getPaginatedUsers(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        return $this->userRepository->paginate($perPage, $columns, $filters);
    }

    /**
     * Find user by ID.
     *
     * @param int|string $id
     * @return User|null
     */
    public function getUserById(int|string $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Find user by username.
     *
     * @param string $username
     * @return User|null
     */
    public function getUserByUsername(string $username): ?User
    {
        return $this->userRepository->findByUsername($username);
    }

    /**
     * Create a new user.
     *
     * @param array<string, mixed> $data
     * @return User
     */
    public function createUser(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if (!isset($data['status'])) {
            $data['status'] = 1;
        }

        return $this->userRepository->create($data);
    }

    /**
     * Update user details.
     *
     * @param int|string $id
     * @param array<string, mixed> $data
     * @return User|null
     */
    public function updateUser(int|string $id, array $data): ?User
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->userRepository->update($id, $data);
    }

    /**
     * Delete user by ID.
     *
     * @param int|string $id
     * @return bool
     */
    public function deleteUser(int|string $id): bool
    {
        return $this->userRepository->delete($id);
    }

    /**
     * Update user active status.
     *
     * @param int|string $id
     * @param int|string $status
     * @return User|null
     */
    public function updateUserStatus(int|string $id, int|string $status): ?User
    {
        return $this->userRepository->updateStatus($id, $status);
    }

    /**
     * Record last login time for user.
     *
     * @param int|string $id
     * @return User|null
     */
    public function recordLastLogin(int|string $id): ?User
    {
        return $this->userRepository->updateLastLogin($id, now());
    }
}
