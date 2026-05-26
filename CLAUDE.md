# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Sainteku is a modular Laravel application for managing academic services at UIN Prof. K.H. Saifuddin Zuhri Purwokerto's Faculty of Science and Technology. Built with Laravel 12 and nwidart/laravel-modules, it provides integrated systems for document management, academic monitoring, infrastructure, achievements, exams, and more.

**Stack**: PHP 8.2+, Laravel 12, MySQL/MariaDB, Vite 7, Tailwind CSS 4, Alpine.js 3.15.8

## Development Commands

### Setup
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

### Running Development Environment
```bash
# All services (server, queue, logs, vite) - RECOMMENDED
composer dev

# Or individually:
php artisan serve              # Dev server at http://127.0.0.1:8000
npm run dev                    # Vite dev server
php artisan queue:listen       # Queue worker for jobs
php artisan pail               # Real-time log monitoring
```

### Testing & Quality
```bash
php artisan test               # Run PHPUnit test suite
composer test                  # Same as above
php artisan pint               # Code style fixer (Laravel Pint)
```

### Module Management
```bash
php artisan module:list        # List all modules and their status
php artisan route:list         # View all registered routes
```

### Build for Production
```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Architecture

### Modular Structure

The application uses `nwidart/laravel-modules` for modular architecture. Each module is self-contained with its own controllers, models, views, routes, and migrations.

**Active Modules**:
- `MasterData` - User, role, unit, course, infrastructure management
- `MonevAkademik` - Exam question bank, tashih (review), proposal workflow
- `DocumentRepository` - Document upload, review, approval workflow
- `ManajemenAchievement` - Student and lecturer achievements, portfolios
- `ManajementInfrastruktur` - Facility/asset borrowing system
- `ManajementEvent` - Campus event management
- `PengaduanMahasiswa` - Student complaint system
- `PenjaminanMutuAkademik` - Academic quality assurance
- `Pelaporan` - Integrated reporting dashboard
- `Ujian` - Online exam system with AI auto-grading

**Module Structure**:
```
Modules/{ModuleName}/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   ├── Services/
│   ├── Jobs/
│   └── Providers/
├── routes/
│   ├── web.php
│   └── api.php
├── resources/views/
└── database/migrations/
```

### Core Application Structure

```
app/
├── Console/Commands/      # CLI commands (e.g., ScrapeNewsCommand)
├── Http/Controllers/      # Core controllers (Auth, Dashboard, Profile, Settings)
├── Jobs/                  # Queue jobs (e.g., ScrapeNewsJob)
├── Models/                # Core models (User, Role, Unit, etc.)
└── Services/              # Shared services (AiService)

resources/
├── views/
│   ├── layouts/          # Base layouts (app.blade.php, guest.blade.php)
│   ├── components/       # Reusable Blade components
│   └── pages/            # Page views (dashboard, landing, etc.)
├── css/
└── js/

routes/
├── web.php               # Main application routes
└── auth.php              # Authentication routes
```

### Database Schema Conventions

**Table Prefixes**:
- `mst_*` - Master data tables (mst_user, mst_role, mst_unit, mst_course, mst_inventory)
- `trx_*` - Transaction tables (trx_user_role, trx_role_permission, trx_document, trx_exam_proposals)
- `ref_*` - Reference/lookup tables (ref_permission)
- `app_*` - Application settings (app_email_settings, ai_settings)

**Key Tables**:
- `mst_user` - Users with custom string IDs (not auto-increment)
- `mst_role` - Roles with codes: ADM (Admin), KPD (Kaprodi), RVI/RVE (Reviewer), DKN (Dosen), MHS (Mahasiswa)
- `mst_module` - Modules for permission system
- `mst_menu` - Dynamic menu system
- `trx_user_role` - Many-to-many user-role relationship
- `trx_role_permission` - Role permissions per module

**Auto-Generated IDs**:
- Courses: `MK001`, `MK002`, etc.
- Infrastructure: `I0001`, `I0002`, etc.

### Permission System

The application uses a custom role-based permission system:

```php
// Check permission in controllers/views
$user->hasPermission($moduleId, $permissionCode)

// Permission codes (from ref_permission):
// C - Create
// R - Read
// U - Update
// D - Delete
// A - Approve/Admin actions
// V - View (special read access)
```

**Admin Role**: Users with role_code `ADM` have all permissions automatically.

**Module IDs** (from mst_module):
- 1 = Document Repository
- 3 = Monev Akademik
- 6 = Manajemen Infrastruktur
- (Check mst_module table for complete list)

### AI Integration

The application includes a multi-provider AI system for exam auto-grading and other AI features.

**Supported Providers**:
- OpenAI (GPT models)
- Anthropic (Claude models)
- Google (Gemini models)
- Groq
- Ollama (local)
- Custom (OpenAI-compatible APIs)

**AI Service Usage**:
```php
use App\Services\AiService;

$aiService = app(AiService::class);
$result = $aiService->sendPrompt($prompt, $setting, $options);

// Returns: ['success' => bool, 'response' => string, 'error' => string|null, 'tokens' => int, 'cost' => float]
```

**AI Auto-Grading**: The Ujian module uses `GradeAttemptJob` to automatically grade essay answers via AI. The job is dispatched to the queue when an exam attempt is submitted.

### Queue System

The application uses Laravel queues for background processing:

**Queue Jobs**:
- `ScrapeNewsJob` - Scrapes news from external sources
- `GradeAttemptJob` - AI-powered exam grading
- Notification jobs for approval workflows

**Running Queue Worker**:
```bash
php artisan queue:listen --tries=1 --timeout=0
# Or use: composer dev (includes queue worker)
```

### Notification System

Internal notification system for workflow approvals stored in `notifications` table. Used for:
- Document review notifications
- Exam proposal review notifications
- Infrastructure borrowing approval notifications

### File Storage

**Storage Disk**: Uses `public` disk for file uploads.

**Important**: Always run `php artisan storage:link` after setup to create the symbolic link from `public/storage` to `storage/app/public`.

**Upload Locations**:
- Infrastructure photos: `storage/app/public/infrastructure/`
- User avatars: `storage/app/public/avatars/`
- User signatures: `storage/app/public/signatures/`
- Documents: `storage/app/public/documents/`

## Workflow Patterns

### Document Repository Workflow
1. User uploads document (status: 1 = Draft, 2 = Pending)
2. Reviewer approves (status: 3 = Approved) or rejects (status: 4 = Revision needed)
3. User can revise and resubmit rejected documents

### Monev Akademik (Exam Tashih) Workflow
1. Dosen creates exam proposal with questions (status: SUBMITTED)
2. Reviewer/Kaprodi reviews and approves/revises (status: APPROVED/REVISED)
3. Approved questions go to bank soal (question bank)
4. All changes logged in `trx_exam_question_logs`

### Infrastructure Borrowing Workflow
1. User submits borrowing request (status: 0 = Pending)
2. Admin approves (status: 1 = Borrowed) or rejects (status: 2 = Rejected)
3. User returns item (status: 3 = Returned)
4. Stock automatically restored on return

## Frontend

**CSS Framework**: Tailwind CSS 4 with Vite plugin
**JavaScript**: Alpine.js for reactive components
**Icons**: Font Awesome 7.2.0
**Code Highlighting**: Prism.js 1.30.0
**Signature Capture**: Signature Pad 5.1.3

**Blade Components**: Located in `resources/views/components/`
- Reusable UI components (buttons, modals, forms, etc.)
- Header components (notifications, user dropdown)

**Layouts**:
- `layouts/app.blade.php` - Authenticated user layout
- `layouts/guest.blade.php` - Guest/login layout
- `layouts/app-header.blade.php` - Header with navigation

## Testing

**Test Framework**: PHPUnit 11.5.3

**Test Configuration**: `phpunit.xml`
- Uses SQLite in-memory database for tests
- Test environment variables configured in phpunit.xml

**Running Tests**:
```bash
php artisan test
# Or: composer test
```

## Seeded Accounts

Default accounts created by `DatabaseSeeder`:
- Admin: `admin@sainteku.ac.id` / `password`
- Dosen: `arifianilhamnurriandana@gmail.com` / `Argtgbgt`
- Kaprodi: `anas@uinsaizu.ac.id` / `kaprodi`
- Mahasiswa: `niamilah@uinsaizu.ac.id` / `password`

## Important Notes

### When Working with Modules

- Each module has its own namespace: `Modules\{ModuleName}\`
- Module routes are automatically loaded from `routes/web.php` and `routes/api.php`
- Module views use namespace: `{modulename}::view.name`
- Always check module is enabled before making changes

### When Working with Permissions

- Always check permissions in controllers before allowing actions
- Admin role (ADM) bypasses all permission checks
- Use `$user->hasPermission($moduleId, $permissionCode)` for checks
- Dashboard shows different cards based on user permissions

### When Working with AI Features

- AI settings are stored in `ai_settings` table
- Always check if AI provider is active and has quota before using
- AI usage is tracked (requests count, total cost, daily quota)
- The `AiService` handles all provider-specific API calls
- Exam auto-grading uses AI via queue jobs (async)

### When Working with Files

- Always use Laravel's Storage facade for file operations
- Validate file uploads (type, size, etc.)
- Store file paths in database, not file contents
- Remember to run `php artisan storage:link` after deployment

### When Working with Migrations

- Module migrations are in `Modules/{ModuleName}/database/migrations/`
- Core migrations are in `database/migrations/`
- Use descriptive migration names with timestamps
- Always test migrations with fresh database

### Code Style

- Follow PSR-12 coding standards
- Use Laravel Pint for automatic formatting: `php artisan pint`
- Use type hints for method parameters and return types
- Write descriptive variable and method names

### Git Workflow

- Main branch: `main`
- Current working branch: `ar`
- Commit messages should be descriptive and follow conventional commits style
