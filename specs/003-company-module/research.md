# Phase 0: Research & Alignment

**Decision**: Use nwidart/laravel-modules for the Company Module, adhering to the Petora Backend Constitution.
**Rationale**: Required by Constitution Principle I (Modular-First Architecture).

**Decision**: Implement Services-DTO-Controller layering.
**Rationale**: Required by Constitution Principle II. Controllers will remain thin, delegating to `CompanyService` using `CompanyDto`.

**Decision**: Use `spatie/laravel-translatable` for `title` and `address` fields.
**Rationale**: Required by Constitution Principle VI for bilingual support (ar/en). Stored as JSON.

**Decision**: Use `spatie/laravel-activitylog` for the Company model.
**Rationale**: Mentioned in spec (logoptions) and Constitution Principle VII.

**Decision**: Use Blade components from `Common` module (`x-common::`) for UI elements in `index`, `create`, `edit` views.
**Rationale**: Required by Constitution Infrastructure rules (Admin Panel).
