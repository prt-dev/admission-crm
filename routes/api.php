<?php

use App\Http\Controllers\Api\AcademicSessionController;
use App\Http\Controllers\Api\AdmissionController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BatchController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Auth Module Routes
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');

        // Protected Auth Routes
        Route::middleware('jwt')->group(function () {
            Route::get('me', [AuthController::class, 'me'])->name('auth.me');
            Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
            Route::post('change-password', [AuthController::class, 'changePassword'])->name('auth.change-password');
        });
    });

    // Permission Module Routes
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('permissions/grouped', [PermissionController::class, 'grouped'])->name('permissions.grouped');
    Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('permissions/{id}', [PermissionController::class, 'show'])->name('permissions.show');
    Route::put('permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    Route::patch('permissions/{id}/status', [PermissionController::class, 'updateStatus'])->name('permissions.update-status');

    // Role Module Routes
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('roles/{id}', [RoleController::class, 'show'])->name('roles.show');
    Route::put('roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::patch('roles/{id}/status', [RoleController::class, 'updateStatus'])->name('roles.update-status');
    Route::get('roles/{id}/permissions', [PermissionController::class, 'getRolePermissions'])->name('roles.permissions.index');
    Route::post('roles/{id}/permissions', [PermissionController::class, 'syncRolePermissions'])->name('roles.permissions.sync');

    // User Module Routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('users/{id}/status', [UserController::class, 'updateStatus'])->name('users.update-status');
    Route::post('users/{id}/last-login', [UserController::class, 'recordLastLogin'])->name('users.last-login');

    // Leads & Inquiries Module Routes
    Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
    Route::post('leads', [LeadController::class, 'store'])->name('leads.store');
    Route::get('leads/{id}', [LeadController::class, 'show'])->name('leads.show');
    Route::put('leads/{id}', [LeadController::class, 'update'])->name('leads.update');
    Route::delete('leads/{id}', [LeadController::class, 'destroy'])->name('leads.destroy');
    Route::patch('leads/{id}/status', [LeadController::class, 'updateStatus'])->name('leads.update-status');
    Route::patch('leads/{id}/assign', [LeadController::class, 'assign'])->name('leads.assign');
    Route::get('leads/{id}/follow-ups', [LeadController::class, 'getFollowUps'])->name('leads.follow-ups.index');
    Route::post('leads/{id}/follow-ups', [LeadController::class, 'addFollowUp'])->name('leads.follow-ups.store');
    Route::patch('leads/{id}/follow-ups/{followUpId}/status', [LeadController::class, 'updateFollowUpStatus'])->name('leads.follow-ups.update-status');

    // Courses Module Routes
    Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
    Route::post('courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('courses/{id}', [CourseController::class, 'show'])->name('courses.show');
    Route::put('courses/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::patch('courses/{id}/status', [CourseController::class, 'updateStatus'])->name('courses.update-status');

    // Batches Module Routes
    Route::get('batches', [BatchController::class, 'index'])->name('batches.index');
    Route::post('batches', [BatchController::class, 'store'])->name('batches.store');
    Route::get('batches/{id}', [BatchController::class, 'show'])->name('batches.show');
    Route::put('batches/{id}', [BatchController::class, 'update'])->name('batches.update');
    Route::delete('batches/{id}', [BatchController::class, 'destroy'])->name('batches.destroy');
    Route::patch('batches/{id}/status', [BatchController::class, 'updateStatus'])->name('batches.update-status');

    // Admissions Module Routes
    Route::get('admissions', [AdmissionController::class, 'index'])->name('admissions.index');
    Route::post('admissions', [AdmissionController::class, 'store'])->name('admissions.store');
    Route::get('admissions/{id}', [AdmissionController::class, 'show'])->name('admissions.show');
    Route::put('admissions/{id}', [AdmissionController::class, 'update'])->name('admissions.update');
    Route::delete('admissions/{id}', [AdmissionController::class, 'destroy'])->name('admissions.destroy');
    Route::patch('admissions/{id}/status', [AdmissionController::class, 'updateStatus'])->name('admissions.update-status');
    Route::post('admissions/{id}/payment', [AdmissionController::class, 'recordPayment'])->name('admissions.record-payment');

    // Attendance Module Routes
    Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::post('attendances/bulk', [AttendanceController::class, 'bulkStore'])->name('attendances.bulk');
    Route::get('attendances/batch/{batchId}', [AttendanceController::class, 'batchAttendance'])->name('attendances.batch');
    Route::get('attendances/batch/{batchId}/summary', [AttendanceController::class, 'batchSummary'])->name('attendances.batch.summary');
    Route::get('attendances/student/{admissionId}/summary', [AttendanceController::class, 'studentSummary'])->name('attendances.student.summary');
    Route::get('attendances/{id}', [AttendanceController::class, 'show'])->name('attendances.show');
    Route::put('attendances/{id}', [AttendanceController::class, 'update'])->name('attendances.update');
    Route::delete('attendances/{id}', [AttendanceController::class, 'destroy'])->name('attendances.destroy');
    Route::patch('attendances/{id}/status', [AttendanceController::class, 'updateStatus'])->name('attendances.update-status');

    // Academic Sessions Module Routes
    Route::get('sessions', [AcademicSessionController::class, 'index'])->name('sessions.index');
    Route::get('sessions/current', [AcademicSessionController::class, 'current'])->name('sessions.current');
    Route::post('sessions', [AcademicSessionController::class, 'store'])->name('sessions.store');
    Route::get('sessions/{id}', [AcademicSessionController::class, 'show'])->name('sessions.show');
    Route::put('sessions/{id}', [AcademicSessionController::class, 'update'])->name('sessions.update');
    Route::delete('sessions/{id}', [AcademicSessionController::class, 'destroy'])->name('sessions.destroy');
    Route::patch('sessions/{id}/status', [AcademicSessionController::class, 'updateStatus'])->name('sessions.update-status');
    Route::post('sessions/{id}/set-current', [AcademicSessionController::class, 'setCurrent'])->name('sessions.set-current');
    Route::get('sessions/{id}/batches', [AcademicSessionController::class, 'batches'])->name('sessions.batches');
});
