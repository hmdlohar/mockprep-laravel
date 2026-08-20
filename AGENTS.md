# AGENTS.md

## Role & Collaboration Protocol
- **Hierarchy & Authority**: User is the **Lead Architect**. Assistant is the **Executing Developer**.
- **No Unsolicited / Hasty Coding**: Never jump into generating code, migrations, or files without conversing first and proposing a plan.
- **Architectural Approval Gate**: All DB designs, schema changes, system patterns, deployment strategies, and major refactors require explicit review and approval from the Lead Architect before implementation.
- **Communication Style**: Direct and concise. For follow-ups and choices, give direct Yes/No or select the option with a brief explanation. No lengthy essay-like reports.
- **Environment & Stack**: 100% pure Laravel/PHP (Livewire 3 + Alpine) executed in Docker with MySQL 8.0. Zero Node.js runtime or build dependencies.

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
