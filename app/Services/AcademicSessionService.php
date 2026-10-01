<?php

namespace App\Services;

use App\Models\AcademicSession;
use App\Repositories\Contracts\AcademicSessionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AcademicSessionService
{
    public function __construct(
        protected AcademicSessionRepositoryInterface $sessionRepository
    ) {}

    public function getPaginatedSessions(int $perPage = 15, array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        return $this->sessionRepository->paginate($perPage, $columns, $filters);
    }

    public function getAllSessions(array $columns = ['*']): Collection
    {
        return $this->sessionRepository->all($columns);
    }

    public function getSessionById(int|string $id): ?AcademicSession
    {
        return $this->sessionRepository->findById($id);
    }

    public function getSessionByCode(string $code): ?AcademicSession
    {
        return $this->sessionRepository->findByCode($code);
    }

    public function getCurrentSession(): ?AcademicSession
    {
        return $this->sessionRepository->getCurrentSession();
    }

    public function createSession(array $data): AcademicSession
    {
        if (empty($data['code'])) {
            $data['code'] = 'SESS-' . str_replace([' ', '/'], '-', strtoupper($data['name']));
        }

        if (!isset($data['status'])) {
            $data['status'] = AcademicSession::STATUS_UPCOMING;
        }

        return $this->sessionRepository->create($data);
    }

    public function updateSession(int|string $id, array $data): ?AcademicSession
    {
        return $this->sessionRepository->update($id, $data);
    }

    public function deleteSession(int|string $id): bool
    {
        return $this->sessionRepository->delete($id);
    }

    public function updateSessionStatus(int|string $id, int $status): ?AcademicSession
    {
        return $this->sessionRepository->updateStatus($id, $status);
    }

    public function setCurrentSession(int|string $id): ?AcademicSession
    {
        return $this->sessionRepository->setCurrentSession($id);
    }

    public function getBatchesBySession(int|string $id): Collection
    {
        return $this->sessionRepository->getBatchesBySession($id);
    }
}
