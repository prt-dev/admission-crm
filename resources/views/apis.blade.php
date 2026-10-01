<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NLETA Admission CRM - API Directory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: #1e293b;
            --bg-card-hover: #334155;
            --border-color: #334155;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent: #38bdf8;
            --accent-glow: rgba(56, 189, 248, 0.15);
            --get-color: #10b981;
            --get-bg: rgba(16, 185, 129, 0.12);
            --post-color: #3b82f6;
            --post-bg: rgba(59, 130, 246, 0.12);
            --put-color: #f59e0b;
            --put-bg: rgba(245, 158, 11, 0.12);
            --patch-color: #8b5cf6;
            --patch-bg: rgba(139, 92, 246, 0.12);
            --delete-color: #ef4444;
            --delete-bg: rgba(239, 68, 68, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-main);
            min-height: 100vh;
            padding: 2.5rem 1.5rem;
        }

        .container {
            max-width: 1300px;
            margin: 0 auto;
        }

        /* Header */
        header {
            margin-bottom: 2.5rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
        }

        .brand h1 {
            font-size: 1.85rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.35rem;
        }

        .brand p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .stats-bar {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .stat-badge {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stat-badge span {
            color: var(--accent);
        }

        /* Search & Filter Controls */
        .controls {
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .search-box {
            position: relative;
            width: 100%;
        }

        .search-box input {
            width: 100%;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            padding: 0.85rem 1.25rem 0.85rem 3rem;
            border-radius: 12px;
            color: var(--text-main);
            font-size: 0.95rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .search-box input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .search-icon {
            position: absolute;
            left: 1.1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
        }

        .module-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .filter-btn {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            padding: 0.45rem 0.9rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-btn:hover {
            color: var(--text-main);
            border-color: var(--accent);
        }

        .filter-btn.active {
            background: var(--accent);
            color: #0f172a;
            border-color: var(--accent);
            font-weight: 700;
        }

        /* Module Sections */
        .module-section {
            margin-bottom: 2.5rem;
        }

        .module-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .module-title .count {
            font-size: 0.75rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--accent);
            padding: 0.15rem 0.6rem;
            border-radius: 9999px;
            font-weight: 600;
        }

        .api-grid {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .api-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.25rem;
            transition: transform 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .api-card:hover {
            border-color: #475569;
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        }

        .api-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
            min-width: 0;
        }

        .method {
            font-family: 'Fira Code', monospace;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.3rem 0.65rem;
            border-radius: 6px;
            min-width: 72px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .method.GET { color: var(--get-color); background: var(--get-bg); border: 1px solid rgba(16, 185, 129, 0.25); }
        .method.POST { color: var(--post-color); background: var(--post-bg); border: 1px solid rgba(59, 130, 246, 0.25); }
        .method.PUT { color: var(--put-color); background: var(--put-bg); border: 1px solid rgba(245, 158, 11, 0.25); }
        .method.PATCH { color: var(--patch-color); background: var(--patch-bg); border: 1px solid rgba(139, 92, 246, 0.25); }
        .method.DELETE { color: var(--delete-color); background: var(--delete-bg); border: 1px solid rgba(239, 68, 68, 0.25); }

        .uri {
            font-family: 'Fira Code', monospace;
            font-size: 0.92rem;
            font-weight: 500;
            color: #e2e8f0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .uri .param {
            color: #38bdf8;
        }

        .api-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .route-name {
            font-size: 0.8rem;
            color: var(--text-muted);
            background: #0f172a;
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            border: 1px solid #334155;
            font-family: 'Fira Code', monospace;
        }

        .action-btns {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-copy {
            background: #0f172a;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.78rem;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-copy:hover {
            color: var(--text-main);
            border-color: var(--accent);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            color: var(--text-muted);
        }

        @media (max-width: 768px) {
            .api-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
            .api-meta {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <div class="brand">
            <h1>NLETA Admission CRM & Academic API Directory</h1>
            <p>RESTful API endpoints reference & testing directory (Base URL: <code>/api/v1</code>)</p>
        </div>
        <div class="stats-bar">
            <div class="stat-badge">Total Endpoints: <span id="total-count">0</span></div>
            <div class="stat-badge">Modules: <span id="module-count">9</span></div>
            <div class="stat-badge">Auth: <span>JWT Bearer</span></div>
        </div>
    </header>

    <div class="controls">
        <div class="search-box">
            <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" id="searchInput" placeholder="Filter by endpoint, HTTP method (GET, POST), route name, or keyword...">
        </div>

        <div class="module-filters" id="filterTabs">
            <button class="filter-btn active" data-filter="all">All Modules</button>
            <button class="filter-btn" data-filter="auth">Auth</button>
            <button class="filter-btn" data-filter="users">Users</button>
            <button class="filter-btn" data-filter="roles">Roles</button>
            <button class="filter-btn" data-filter="permissions">Permissions</button>
            <button class="filter-btn" data-filter="leads">Leads & Follow-ups</button>
            <button class="filter-btn" data-filter="courses">Courses</button>
            <button class="filter-btn" data-filter="batches">Batches</button>
            <button class="filter-btn" data-filter="admissions">Admissions</button>
            <button class="filter-btn" data-filter="attendances">Attendances</button>
            <button class="filter-btn" data-filter="sessions">Academic Sessions</button>
        </div>
    </div>

    <main id="apiContainer">
        <!-- API Modules rendered here -->
    </main>

    <div id="noResults" class="empty-state" style="display: none;">
        <h3>No matching API endpoints found</h3>
        <p>Try searching with another keyword or selecting "All Modules".</p>
    </div>
</div>

<script>
    const apiData = [
        {
            module: "auth",
            moduleTitle: "Authentication Module",
            endpoints: [
                { method: "POST", uri: "/api/v1/auth/login", name: "auth.login", desc: "User login with username/email & password, returns JWT token" },
                { method: "GET", uri: "/api/v1/auth/me", name: "auth.me", desc: "Get authenticated user profile and roles (Requires JWT)" },
                { method: "POST", uri: "/api/v1/auth/logout", name: "auth.logout", desc: "Logout user and invalidate authentication cookie" },
                { method: "POST", uri: "/api/v1/auth/change-password", name: "auth.change-password", desc: "Update current user password" }
            ]
        },
        {
            module: "users",
            moduleTitle: "User Management Module",
            endpoints: [
                { method: "GET", uri: "/api/v1/users", name: "users.index", desc: "List paginated users with filters (role, status, search)" },
                { method: "POST", uri: "/api/v1/users", name: "users.store", desc: "Create a new user account" },
                { method: "GET", uri: "/api/v1/users/{id}", name: "users.show", desc: "Get user details by ID" },
                { method: "PUT", uri: "/api/v1/users/{id}", name: "users.update", desc: "Update existing user details" },
                { method: "DELETE", uri: "/api/v1/users/{id}", name: "users.destroy", desc: "Delete a user" },
                { method: "PATCH", uri: "/api/v1/users/{id}/status", name: "users.update-status", desc: "Toggle user active/inactive status" },
                { method: "POST", uri: "/api/v1/users/{id}/last-login", name: "users.last-login", desc: "Record user last login timestamp" }
            ]
        },
        {
            module: "roles",
            moduleTitle: "Role Management Module",
            endpoints: [
                { method: "GET", uri: "/api/v1/roles", name: "roles.index", desc: "List all roles with permission count" },
                { method: "POST", uri: "/api/v1/roles", name: "roles.store", desc: "Create a new role" },
                { method: "GET", uri: "/api/v1/roles/{id}", name: "roles.show", desc: "Get role details and assigned permissions" },
                { method: "PUT", uri: "/api/v1/roles/{id}", name: "roles.update", desc: "Update role details" },
                { method: "DELETE", uri: "/api/v1/roles/{id}", name: "roles.destroy", desc: "Delete role" },
                { method: "PATCH", uri: "/api/v1/roles/{id}/status", name: "roles.update-status", desc: "Update role status" },
                { method: "GET", uri: "/api/v1/roles/{id}/permissions", name: "roles.permissions.index", desc: "Get permissions assigned to a role" },
                { method: "POST", uri: "/api/v1/roles/{id}/permissions", name: "roles.permissions.sync", desc: "Sync / assign permissions to a role" }
            ]
        },
        {
            module: "permissions",
            moduleTitle: "Permission Management Module",
            endpoints: [
                { method: "GET", uri: "/api/v1/permissions", name: "permissions.index", desc: "List all permissions" },
                { method: "GET", uri: "/api/v1/permissions/grouped", name: "permissions.grouped", desc: "List permissions grouped by module" },
                { method: "POST", uri: "/api/v1/permissions", name: "permissions.store", desc: "Create a new permission" },
                { method: "GET", uri: "/api/v1/permissions/{id}", name: "permissions.show", desc: "Get permission details" },
                { method: "PUT", uri: "/api/v1/permissions/{id}", name: "permissions.update", desc: "Update permission" },
                { method: "DELETE", uri: "/api/v1/permissions/{id}", name: "permissions.destroy", desc: "Delete permission" },
                { method: "PATCH", uri: "/api/v1/permissions/{id}/status", name: "permissions.update-status", desc: "Update permission status" }
            ]
        },
        {
            module: "leads",
            moduleTitle: "Leads & Inquiries Module",
            endpoints: [
                { method: "GET", uri: "/api/v1/leads", name: "leads.index", desc: "List leads with filters (status, counselor, search)" },
                { method: "POST", uri: "/api/v1/leads", name: "leads.store", desc: "Capture new student lead / inquiry" },
                { method: "GET", uri: "/api/v1/leads/{id}", name: "leads.show", desc: "Get lead profile & follow-up logs" },
                { method: "PUT", uri: "/api/v1/leads/{id}", name: "leads.update", desc: "Update lead information" },
                { method: "DELETE", uri: "/api/v1/leads/{id}", name: "leads.destroy", desc: "Delete lead record" },
                { method: "PATCH", uri: "/api/v1/leads/{id}/status", name: "leads.update-status", desc: "Update lead status (New, Contacted, Converted)" },
                { method: "PATCH", uri: "/api/v1/leads/{id}/assign", name: "leads.assign", desc: "Assign lead to an admission counselor" },
                { method: "GET", uri: "/api/v1/leads/{id}/follow-ups", name: "leads.follow-ups.index", desc: "Get all follow-up notes for a lead" },
                { method: "POST", uri: "/api/v1/leads/{id}/follow-ups", name: "leads.follow-ups.store", desc: "Schedule / log a follow-up interaction" },
                { method: "PATCH", uri: "/api/v1/leads/{id}/follow-ups/{followUpId}/status", name: "leads.follow-ups.update-status", desc: "Update follow-up completion status" }
            ]
        },
        {
            module: "courses",
            moduleTitle: "Training Courses Module",
            endpoints: [
                { method: "GET", uri: "/api/v1/courses", name: "courses.index", desc: "List all courses, fees, and durations" },
                { method: "POST", uri: "/api/v1/courses", name: "courses.store", desc: "Create a new course" },
                { method: "GET", uri: "/api/v1/courses/{id}", name: "courses.show", desc: "Get course curriculum and details" },
                { method: "PUT", uri: "/api/v1/courses/{id}", name: "courses.update", desc: "Update course details" },
                { method: "DELETE", uri: "/api/v1/courses/{id}", name: "courses.destroy", desc: "Delete course" },
                { method: "PATCH", uri: "/api/v1/courses/{id}/status", name: "courses.update-status", desc: "Toggle course active/inactive status" }
            ]
        },
        {
            module: "batches",
            moduleTitle: "Batches & Cohorts Module",
            endpoints: [
                { method: "GET", uri: "/api/v1/batches", name: "batches.index", desc: "List batches with capacity, course, and instructor" },
                { method: "POST", uri: "/api/v1/batches", name: "batches.store", desc: "Create a new batch cohort" },
                { method: "GET", uri: "/api/v1/batches/{id}", name: "batches.show", desc: "Get batch details, enrolled students count" },
                { method: "PUT", uri: "/api/v1/batches/{id}", name: "batches.update", desc: "Update batch timing, dates, or instructor" },
                { method: "DELETE", uri: "/api/v1/batches/{id}", name: "batches.destroy", desc: "Delete batch" },
                { method: "PATCH", uri: "/api/v1/batches/{id}/status", name: "batches.update-status", desc: "Update batch status (Upcoming, Ongoing, Completed)" }
            ]
        },
        {
            module: "admissions",
            moduleTitle: "Student Admissions Module",
            endpoints: [
                { method: "GET", uri: "/api/v1/admissions", name: "admissions.index", desc: "List student admissions with filters (course, batch, fee status)" },
                { method: "POST", uri: "/api/v1/admissions", name: "admissions.store", desc: "Enroll student / create admission record" },
                { method: "GET", uri: "/api/v1/admissions/{id}", name: "admissions.show", desc: "Get student admission details & payment ledger" },
                { method: "PUT", uri: "/api/v1/admissions/{id}", name: "admissions.update", desc: "Update student admission data" },
                { method: "DELETE", uri: "/api/v1/admissions/{id}", name: "admissions.destroy", desc: "Delete admission record" },
                { method: "PATCH", uri: "/api/v1/admissions/{id}/status", name: "admissions.update-status", desc: "Update admission status" },
                { method: "POST", uri: "/api/v1/admissions/{id}/payment", name: "admissions.record-payment", desc: "Record fee payment and recalculate due balance" }
            ]
        },
        {
            module: "attendances",
            moduleTitle: "Training Attendance Module",
            endpoints: [
                { method: "GET", uri: "/api/v1/attendances", name: "attendances.index", desc: "List attendance logs with date range, batch, type (T/P/O), status" },
                { method: "POST", uri: "/api/v1/attendances", name: "attendances.store", desc: "Record individual student attendance (date, duration, type, status)" },
                { method: "POST", uri: "/api/v1/attendances/bulk", name: "attendances.bulk", desc: "Mark bulk attendance for entire batch session" },
                { method: "GET", uri: "/api/v1/attendances/batch/{batchId}", name: "attendances.batch", desc: "Get attendance for a specific batch on a given date" },
                { method: "GET", uri: "/api/v1/attendances/batch/{batchId}/summary", name: "attendances.batch.summary", desc: "Get batch attendance summary (Theory, Practical, OJT hours)" },
                { method: "GET", uri: "/api/v1/attendances/student/{admissionId}/summary", name: "attendances.student.summary", desc: "Get student attendance percentage & hours breakdown by T, P, O" },
                { method: "GET", uri: "/api/v1/attendances/{id}", name: "attendances.show", desc: "Get attendance record details" },
                { method: "PUT", uri: "/api/v1/attendances/{id}", name: "attendances.update", desc: "Update attendance entry" },
                { method: "DELETE", uri: "/api/v1/attendances/{id}", name: "attendances.destroy", desc: "Delete attendance entry" },
                { method: "PATCH", uri: "/api/v1/attendances/{id}/status", name: "attendances.update-status", desc: "Update attendance status (Present, Absent, Late, Leave)" }
            ]
        },
        {
            module: "sessions",
            moduleTitle: "Academic Sessions Module",
            endpoints: [
                { method: "GET", uri: "/api/v1/sessions", name: "sessions.index", desc: "List all academic session years (e.g. 2025-2026, 2026-2027)" },
                { method: "GET", uri: "/api/v1/sessions/current", name: "sessions.current", desc: "Get the current active academic session" },
                { method: "POST", uri: "/api/v1/sessions", name: "sessions.store", desc: "Create a new academic session" },
                { method: "GET", uri: "/api/v1/sessions/{id}", name: "sessions.show", desc: "Get academic session details with batch & student counts" },
                { method: "PUT", uri: "/api/v1/sessions/{id}", name: "sessions.update", desc: "Update academic session dates or status" },
                { method: "DELETE", uri: "/api/v1/sessions/{id}", name: "sessions.destroy", desc: "Delete academic session" },
                { method: "PATCH", uri: "/api/v1/sessions/{id}/status", name: "sessions.update-status", desc: "Update academic session status" },
                { method: "POST", uri: "/api/v1/sessions/{id}/set-current", name: "sessions.set-current", desc: "Set academic session as the active ongoing session" },
                { method: "GET", uri: "/api/v1/sessions/{id}/batches", name: "sessions.batches", desc: "Get all batches running under this academic session" }
            ]
        }
    ];

    function formatUri(uri) {
        return uri.replace(/\{([^}]+)\}/g, '<span class="param">{$1}</span>');
    }

    function renderApis(filteredModule = 'all', searchQuery = '') {
        const container = document.getElementById('apiContainer');
        const noResults = document.getElementById('noResults');
        let totalRendered = 0;
        let html = '';

        const query = searchQuery.trim().toLowerCase();

        apiData.forEach(mod => {
            if (filteredModule !== 'all' && mod.module !== filteredModule) {
                return;
            }

            const matchingEndpoints = mod.endpoints.filter(ep => {
                if (!query) return true;
                return ep.uri.toLowerCase().includes(query) ||
                       ep.method.toLowerCase().includes(query) ||
                       ep.name.toLowerCase().includes(query) ||
                       ep.desc.toLowerCase().includes(query) ||
                       mod.moduleTitle.toLowerCase().includes(query);
            });

            if (matchingEndpoints.length > 0) {
                totalRendered += matchingEndpoints.length;
                html += `
                    <section class="module-section" data-module="${mod.module}">
                        <div class="module-title">
                            <span>${mod.moduleTitle}</span>
                            <span class="count">${matchingEndpoints.length}</span>
                        </div>
                        <div class="api-grid">
                `;

                matchingEndpoints.forEach(ep => {
                    html += `
                        <div class="api-card">
                            <div class="api-info">
                                <span class="method ${ep.method}">${ep.method}</span>
                                <div>
                                    <div class="uri">${formatUri(ep.uri)}</div>
                                    <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 0.2rem;">${ep.desc}</div>
                                </div>
                            </div>
                            <div class="api-meta">
                                <span class="route-name">${ep.name}</span>
                                <div class="action-btns">
                                    <button class="btn-copy" onclick="copyToClipboard('${ep.uri}', this)">Copy URI</button>
                                    ${ep.method === 'GET' && !ep.uri.includes('{') ? `<a href="${ep.uri}" target="_blank" class="btn-copy" style="text-decoration:none; display:inline-block;">Test GET</a>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                });

                html += `
                        </div>
                    </section>
                `;
            }
        });

        container.innerHTML = html;
        document.getElementById('total-count').innerText = totalRendered;
        noResults.style.display = totalRendered === 0 ? 'block' : 'none';
    }

    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(window.location.origin + text).then(() => {
            const original = btn.innerText;
            btn.innerText = 'Copied!';
            btn.style.borderColor = '#10b981';
            btn.style.color = '#10b981';
            setTimeout(() => {
                btn.innerText = original;
                btn.style.borderColor = '';
                btn.style.color = '';
            }, 1500);
        });
    }

    // Filter tabs
    let currentFilter = 'all';
    document.getElementById('filterTabs').addEventListener('click', (e) => {
        if (e.target.classList.contains('filter-btn')) {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            e.target.classList.add('active');
            currentFilter = e.target.dataset.filter;
            renderApis(currentFilter, document.getElementById('searchInput').value);
        }
    });

    // Search input
    document.getElementById('searchInput').addEventListener('input', (e) => {
        renderApis(currentFilter, e.target.value);
    });

    // Initial render
    renderApis();
</script>

</body>
</html>
