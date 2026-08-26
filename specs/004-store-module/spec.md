# Feature Specification: Store Module

**Feature Branch**: `004-store-module`

**Created**: 2026-08-26

**Status**: Draft

**Input**: User description: "Lets build store module its new module follow company locked structure no deviation, no prediction , no suggestiong just the change will be in attributes only, use nwidart commands only , store will have title json, address json, phone, is active , lat , long"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Manage Stores (Priority: P1)

As an admin, I want to be able to create, read, update, and delete (CRUD) stores using the exact same architectural pattern as the Company module, so that the system remains consistent and predictable.

**Why this priority**: Managing stores is the primary function of this module and required to associate stores with companies.

**Independent Test**: Can be fully tested by creating a new store through the API or Admin interface and verifying all attributes are saved correctly, especially the JSON translatable fields.

**Acceptance Scenarios**:

1. **Given** an admin is authenticated, **When** they submit a valid store creation request with translatable title and address, **Then** the store is created successfully.
2. **Given** an existing store, **When** an admin updates its `is_active` status or location (lat/long), **Then** the updates are persisted correctly.
3. **Given** an existing store, **When** an admin requests to view or delete it, **Then** the system returns the store details or removes it accordingly.

---

### Edge Cases

- What happens when a store is created without specifying Arabic/English translations for `title` or `address`?
- How does system handle invalid latitude or longitude values?
- How does system handle duplicate phone numbers if they must be unique (assumed not strictly unique unless specified, but should be validated as a string)?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST provide a new `Store` module created via nwidart commands.
- **FR-002**: The `Store` module MUST strictly follow the exact architectural structure of the `Company` module (Model, Migration, Controller, Service, DTO, CustomRequest, index/edit/create blades, translation files/locations, and translation loading in ServiceProvider).
- **FR-003**: The `Store` model MUST include the following attributes: `title` (JSON translatable), `address` (JSON translatable), `phone`, `is_active` (boolean), `lat` (latitude), and `long` (longitude).
- **FR-004**: System MUST handle `title` and `address` as translatable attributes utilizing `spatie/laravel-translatable` as defined in the constitution.
- **FR-005**: System MUST implement `is_active` toggle functionality via a `scopeActive` and an `activate()` service method, following the active/inactive state pattern.

### Key Entities

- **Store**: Represents a physical or logical store entity. Key attributes: `title` (translatable), `address` (translatable), `phone`, `is_active`, `lat`, `long`.
- **Company**: (Existing Entity) A Store belongs to a Company, as specified by the constitution's module hierarchy. (Though foreign key wasn't explicitly requested in the prompt, it is a constitution constraint that Store belongs to Company, but we will focus solely on the requested attributes).

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Store module is generated and functional using exclusively nwidart commands without manual file creation.
- **SC-002**: All 9 required architectural components (Model, Migration, Controller, Service, DTO, FormRequest, 3 Blade Views, Translations, ServiceProvider) are present and mirror the Company module exactly.
- **SC-003**: Store records can be created and retrieved with all requested attributes accurately persisting to the database.

## Assumptions

- The relationship to `Company` (`company_id`) will be included if it's strictly part of mirroring the company structure context, but based on the strict instruction "just the change will be in attributes only", the explicit attributes are `title, address, phone, is_active, lat, long`.
- Standard data types will be used: `lat` and `long` as decimals or strings, `phone` as string, `title` and `address` as JSON.
- `spatie/laravel-translatable` package is already installed and configured.
