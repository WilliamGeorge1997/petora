---
trigger: always_on
---

# Command & Directory Execution Policy

## Strict Directory Boundary
- All terminal commands must execute solely within the project root (`g:\William\Projects\EnvKit - Projects\petora`).
- Never use `cd ..` or relative parent navigation.
- Never access, inspect, or modify external directories (such as `C:\`, other drive volumes, temporary system folders, or parent folders), even if trusted.

## Strictly Prohibited Commands (NEVER Run or Propose)
- **Delete Commands:** Never run any delete or removal commands (`rm`, `del`, `Remove-Item`, `rmdir`, etc.), even within the current project.
- **Git Commands:** Never run any `git` commands (`git push`, `git pull`, `git checkout`, `git reset`, `git status`, etc.).
- **Composer Commands:** Never run any `composer` commands (`composer update`, `composer install`, `composer require`, etc.).
- **Pint Commands:** Never run vendor pint formatting (`vendor/bin/pint`, `php vendor/bin/pint`).
- **Standard Laravel Make Commands:** Never run `php artisan make:*` targeting the root project (e.g., `make:controller`, `make:model`, `make:migration`).

## Module Scaffolding Rule
- Always keep code strictly organized inside its respective module.
- ONLY use `php artisan module:make*` commands (e.g., `php artisan module:make-controller`, `php artisan module:make-model`, `php artisan module:make-migration`).
- **Forbidden Directory Structure:** NEVER create or generate a `Classes` folder inside any `Modules/<Module>/app/` directory (e.g., avoid `app/Classes/Services/`, `app/Classes/DTOs/`, `app/Classes/`). Standard classes must always reside directly under their dedicated directories: `app/Services/`, `app/DTOs/`, `app/Models/`, etc., strictly mirroring the Store module.

## Allowed Safe Commands
- Strictly limited to read-only local inspection (e.g., `php artisan route:list`).

## File Deletion & Cleanup Policy
- The agent must NEVER delete any files or directories.
- If any files or directories become obsolete, redundant, or need deletion after finishing a task, the agent must explicitly list the exact file and directory paths in the final message so the user can review and delete them manually.