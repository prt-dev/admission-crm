<?php

namespace App\Providers;

use App\Repositories\Contracts\AcademicSessionRepositoryInterface;
use App\Repositories\Contracts\AdmissionRepositoryInterface;
use App\Repositories\Contracts\AttendanceRepositoryInterface;
use App\Repositories\Contracts\BatchRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\LeadRepositoryInterface;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\AcademicSessionRepository;
use App\Repositories\Eloquent\AdmissionRepository;
use App\Repositories\Eloquent\AttendanceRepository;
use App\Repositories\Eloquent\BatchRepository;
use App\Repositories\Eloquent\CourseRepository;
use App\Repositories\Eloquent\LeadRepository;
use App\Repositories\Eloquent\PermissionRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        PermissionRepositoryInterface::class => PermissionRepository::class,
        RoleRepositoryInterface::class => RoleRepository::class,
        UserRepositoryInterface::class => UserRepository::class,
        LeadRepositoryInterface::class => LeadRepository::class,
        CourseRepositoryInterface::class => CourseRepository::class,
        BatchRepositoryInterface::class => BatchRepository::class,
        AdmissionRepositoryInterface::class => AdmissionRepository::class,
        AttendanceRepositoryInterface::class => AttendanceRepository::class,
        AcademicSessionRepositoryInterface::class => AcademicSessionRepository::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(LeadRepositoryInterface::class, LeadRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(BatchRepositoryInterface::class, BatchRepository::class);
        $this->app->bind(AdmissionRepositoryInterface::class, AdmissionRepository::class);
        $this->app->bind(AttendanceRepositoryInterface::class, AttendanceRepository::class);
        $this->app->bind(AcademicSessionRepositoryInterface::class, AcademicSessionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
