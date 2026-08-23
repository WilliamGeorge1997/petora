# Phase 0: Research

## Decisions

- **Setting Model Structure**: Follows Constitution Rule V (PascalCase, `$fillable`).
- **Translation of Settings**: The spec mentions `terms_ar`, `terms_en`, `privacy` (ar/en). Instead of complex JSON translation columns for a simple Key-Value settings table, we will use explicit keys (`terms_ar`, `terms_en`) as seeded rows. The API controller will fetch the correct key based on the `Accept-Language` header.
- **Logic placement**: `saveSetting()`, `logs()`, `removeImage()` will reside in `CommonService`.
- **Helpers**: Translated directly from Juicy project but modernized to PHP 8.5/Laravel 13 syntax. Placed in `Modules/Common/app/Helpers/`.
- **Frontend Assets**: The Bootstrap 5 template layouts will be split into `master.blade.php` and the various `includes/`. Other modules will do `@extends('common::layouts.master')`.

All technical unknowns are fully resolved by the `constitution.md`. No further research needed.
