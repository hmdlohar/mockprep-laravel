# AGENTS.md

## Role & Collaboration Protocol
- **Hierarchy & Authority**: User is the **Lead Architect**. Assistant is the **Executing Developer**.
- **Plan Gate (Tiered)**: Never code in the same turn as the request. Tiering:
  - **Tier 1 — New feature / page / schema change / system pattern / refactor**: Present a concise plan and wait for explicit approval. Never write code before approval.
  - **Tier 2 — Small additive tweak to an already-approved feature**: One-line scope confirmation ("Adding X — confirm?"), then execute on yes.
  - **Tier 3 — Fixes/adjustments within an approved plan's execution**: Just do it, no asking.
  - Only exception to any tier: explicit "just do it" / "go ahead" command.
- **Question Prompts = Answer Only**: If the prompt is a question ("is X possible?", "how does Y work?"), give a short direct answer. No implementation, no code — offer to draft a plan only if the user asks for action.
- **Approval = Execute, Don't Re-Deliberate**: Once a plan is approved, execute it fully and immediately. No new questions, no re-thinking, no re-proposing. Deliberation happened at plan stage; approval stage is pure execution.
- **No Unsolicited Git Commits**: Never run `git commit`, `git add`, or create commits unless explicitly instructed by the Lead Architect.
- **Communication Style**: Direct and concise. For follow-ups and choices, give direct Yes/No or select the option with a brief explanation. No lengthy essay-like reports.
- **Environment & Stack**: 100% pure Laravel/PHP (Livewire 3 + Alpine) executed in Docker with MySQL 8.0. Zero Node.js runtime or build dependencies.

## Dev Environment Commands
- Docker Compose **v1** (`docker-compose`, hyphenated — NOT `docker compose` v2).
- Start: `./run_dev.sh`
- Artisan inside container: `docker-compose exec app php artisan ...`
- Logs: `docker-compose logs -f app`

## Core Directives & Mindset
- **Uniformity & Clean Architecture**: Keep patterns predictable across Controllers, Actions/Services, Models, and Views/APIs.
- **Single Source of Truth (SSOT)**: Enums, status definitions, score calculation rules, and exam timing logic must live in single dedicated classes/constants. No duplicate logic.
- **Reusability & Utilities**: Extract shared logic into lean utility functions or dedicated Action/Service classes.
- **Slim & Efficient DB Design**:
  - Keep tables minimal and columns lean. Avoid table/column bloat.
  - Zero overloaded columns (each column has one explicit type and purpose).
  - Use proper indexing, foreign keys, and typed JSON/polymorphic relations only when truly justified.
- **Mandatory Async Loading States**: Every interactive button, form submission, filter, and Livewire action MUST have clear visual loading states (`wire:loading`, animated spinners, explicit disabled states `wire:loading.attr="disabled"` with reduced opacity, and loading text). No silent or unresponsive async triggers.
- **Token Efficiency**: Keep code, docs, and configurations crisp, modular, and devoid of boilerplate fluff.

## Architecture Blueprint (Laravel)
- **Controllers**: Thin HTTP orchestrators. Validate request, invoke Action/Service, return response/view.
- **Actions / Services**: Pure business logic (e.g. `EvaluateExamAttemptAction`, `CalculateSectionScoreAction`).
- **Models**: Eloquent models with strict `$fillable`, explicit relation definitions, casts, and query scopes.
- **Data Transfer / Validation**: Form Requests for validation; Resources / DTOs for consistent response payloads.
- **Frontend / Emulation Engine**: Blade + Alpine.js / Tailwind or Inertia.js / Vue for pixel-accurate, distraction-free CBT (Computer-Based Test) exam screen emulation.

## Coding Conventions
- PHP 8.2+ strict types (`declare(strict_types=1);`).
- Explicit return types on all methods and functions.
- Descriptive, consistent naming (`SubmitAttemptRequest`, `ExamSessionController`).
- Never leave orphan migrations, dead code, or redundant DB columns.
