# Project Implementation Plan: MockPrep CBT Platform

## Tech Stack & Architecture
- **Framework**: Laravel 11 (PHP 8.3)
- **Database**: MySQL 8.0
- **Frontend / Reactivity**: Laravel Livewire 3 + Alpine.js (100% Pure PHP/Blade, zero Node.js)
- **Styling**: Tailwind CSS (CDN / Standalone binary)
- **Runtime**: Docker & Docker Compose

---

## Phase Breakdown

### Phase 1: Environment & Project Scaffolding
- [x] Create `docker-compose.yml` (PHP 8.3 CLI/FPM + MySQL 8.0).
- [x] Scaffold clean Laravel 11 application.
- [x] Configure MySQL connection in `.env` (connected to Hostinger remote MySQL).
- [x] Install and configure **Livewire 3**.
- [x] Add `run_dev.sh` helper script for dev management.

### Phase 2: Database Migrations, Models & Enums
- [x] Create migrations strictly matching `plans/database-schema.md`.
- [x] Build strict Eloquent models with typed casts, `$fillable`, and relationships:
  - `User`, `Topic`, `Passage`, `Question`, `Test`, `TestSection`, `Package`, `ExamAttempt`, `AttemptAnswer`.
- [x] Implement typed PHP Enums: `ExamCategory`, `QuestionType`, `AttemptStatus`, `AnswerStatus`, `SectionCategory`.
- [x] Create comprehensive database seeders with realistic CAT/CMAT test data.

### Phase 3: Central Question Bank & Test Builder Engine
- [x] **Question Bank**: Livewire CRUD for questions, RC/DILR passages, and topic tagging.
- [x] **Test Builder Engine**: Rule-based blueprint generator (selecting questions by section, difficulty range, topics, and passage sets into locked snapshot tests).

### Phase 4: CBT Exam Screen Emulation Engine
- [x] Authentic CAT/CMAT CBT Interface:
  - Header: Candidate info, remaining timer countdown, sectional navigation tabs.
  - Question View: Rich HTML formatting, MCQ radio selection, TITA numeric keypad/input.
  - Action Palette: Save & Next, Clear Response, Mark for Review & Next.
  - Interactive Status Grid: (Not Visited, Not Answered, Answered, Marked for Review, Answered & Marked).
  - Floating On-screen Calculator modal.
- [x] Autosave answer sync via Livewire state engine with zero page reloads.

### Phase 5: Scoring Engine & Post-Test Analytics
- [x] `EvaluateExamAttemptAction`: Server-side scoring (positive marks, negative marks for MCQ, zero negative for TITA).
- [x] Section-wise and topic-wise performance breakdown, accuracy charts, and detailed question solutions.
