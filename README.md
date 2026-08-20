# On Your Mocks (MockPrep Laravel)

> **High-Performance CBT (Computer-Based Test) Exam & Practice Platform**  
> *100% Pure Laravel 11 + Livewire 3 + Alpine.js | MySQL 8.0 | Zero Node.js Runtime/Build Dependencies*

---

## 🚀 Key Architectural Pillars

1. **Pure PHP/Blade Stack**: Built strictly using **Laravel 11, Livewire 3, Alpine.js, and Tailwind CSS (via CDN)**. No Node.js build steps (`npm run build`), no Webpack/Vite complexity, and zero frontend runtime dependencies on the server.
2. **Authentic CBT Exam Emulation**: Pixel-accurate simulation of national CAT / CMAT / XAT exam screens (Sectional timers, question status palette, floating scientific calculator, keyboard-free navigation, and instant autosave).
3. **Extensible Bulk Question Importer**: Multi-format question ingestion engine (`app/Services/Import/`) with JSONP stripping, Netlify image proxy transformation, duplicate detection, and topic taxonomy mapping.
4. **Unbroken Question-Set Guarantee**: Invariant engine (`QuestionSetPicker.php`) ensuring Reading Comprehension and DILR passage sets are never split or broken when building tests from imported pools.
5. **Production Ready**: Optimized for Hostinger Web Hosting and VPS Docker deployments with database session/cache stores and forced HTTPS routing.

---

## 🛠️ Architecture & Directory Overview

```text
app/
├── Actions/
│   ├── EvaluateExamAttemptAction.php     # Pure scoring engine (+3 correct, -1 incorrect for MCQ, 0 for TITA)
│   └── BuildTestFromBlueprintAction.php  # Test blueprint assembly action
├── Console/Commands/
│   ├── MakeAdminCommand.php              # Promotes/creates admin via `php artisan make:admin`
│   └── BulkImportFolderCommand.php       # CLI batch folder importer
├── Enums/
│   ├── ExamCategory.php                  # CAT, XAT, SNAP, NMAT, CMAT, IIFT, MHCET
│   ├── SectionCategory.php               # VA, DILR, QA
│   ├── QuestionType.php                  # MCQ, TITA
│   ├── AttemptStatus.php                 # IN_PROGRESS, COMPLETED, TIMED_OUT, ABANDONED
│   ├── AnswerStatus.php                  # NOT_VISITED, NOT_ANSWERED, ANSWERED, MARKED_FOR_REVIEW, ANSWERED_AND_MARKED
│   └── UserRole.php                      # ADMIN, STUDENT
├── Http/
│   ├── Controllers/GoogleAuthController.php # Socialite Google OAuth handler
│   └── Middleware/AdminMiddleware.php      # Guards `/admin/*` routes
├── Livewire/
│   ├── Admin/
│   │   ├── Dashboard.php                 # Metrics overview
│   │   ├── QuestionIndex.php             # Question Lake with source & section filters
│   │   ├── QuestionImporter.php          # 2-step staging importer & direct test generator
│   │   ├── PassageIndex.php              # Reading Comprehension & DILR caselet management
│   │   ├── TestIndex.php                 # Test catalog management
│   │   ├── TestBuilder.php               # Visual test creator
│   │   └── PackageIndex.php              # Test series bundling & pricing
│   ├── Auth/                             # Login & Registration components
│   ├── Cbt/ExamRunner.php                # High-speed CBT Exam runner
│   └── Portal/                           # Catalog, Instructions, Results, Onboarding
└── Services/Import/
    ├── Contracts/QuestionImportParserInterface.php # Extensible parser contract
    ├── Parsers/JsonpQuestionParser.php             # JSONP & Netlify image proxy transformer
    ├── DuplicateDetector.php                       # Exact (source, external_id) + content hashing
    ├── QuestionSetPicker.php                       # Atomic unbroken question-set selector
    └── Actions/CommitImportAction.php              # Database persistence & snapshot test builder
```

---

## ⚙️ Local Development (Docker)

The project runs inside Docker with PHP 8.3 CLI/FPM and MySQL 8.0:

```bash
# 1. Start Docker containers
docker-compose up -d

# 2. Run Database Migrations
docker-compose exec app php artisan migrate

# 3. Seed Sample Data (Optional)
docker-compose exec app php artisan db:seed

# 4. Create an Admin Account
docker-compose exec app php artisan make:admin hmdlohar@gmail.com
```

The application is available locally at: **`http://localhost:8000`**

---

## 🌐 Production Deployment (Hostinger hPanel)

1. **Connect Git Repository**:
   - In Hostinger hPanel, go to **Advanced $\rightarrow$ Git**.
   - Install Path: `public_html`
   - Branch: `main`
2. **Document Root**:
   - Point Document Root to `public_html/public` (or rely on the root `.htaccess` included in the repo).
3. **Environment Setup (`public_html/.env`)**:
   ```dotenv
   APP_NAME="MockPrep"
   APP_ENV=production
   APP_KEY=base64:...
   APP_DEBUG=false
   APP_URL=https://yourdomain.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=u280428969_mp_laravel
   DB_USERNAME=u280428969_mp_laravel
   DB_PASSWORD=your_password

   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database

   GOOGLE_CLIENT_ID=your_google_client_id
   GOOGLE_CLIENT_SECRET=your_google_client_secret
   ```
4. **Run Initial Setup (via SSH)**:
   ```bash
   cd public_html
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan migrate --force
   php artisan make:admin your_email@gmail.com
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## 🛡️ Developer Rules & Protocols

All developers and AI pair programmers must strictly adhere to the guidelines set in [`AGENTS.md`](./AGENTS.md):
- **Hierarchy**: User is the Lead Architect; Developer is the Executor. Propose plans before generating code or migrations.
- **No Unsolicited Commits**: Never run `git commit` unless explicitly instructed by the Lead Architect.
- **Mandatory Async Loading States**: Every interactive button, filter, and form submission must feature explicit loading states (`wire:loading`, animated SVG spinners, and disabled attributes).
- **Pure Laravel Stack**: Zero Node.js or npm build dependencies allowed in the codebase.

