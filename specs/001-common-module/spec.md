# Feature Specification: Common Module

**Feature Branch**: `[001-common-module]`
**Created**: 2026-08-23
**Status**: Draft
**Input**: User description: "model setting for general application setting... helpers similar to juicy... handle inlcudes navbar... handle components... commoncontroller will have saveSetting... seeding data terms_ar..."

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Shared UI Components & Layouts (Priority: P1)
As a developer, I want to use reusable Blade layouts, includes, and components so that the Admin Panel UI is consistent and duplication is eliminated.
**Why this priority**: All other modules depend on these layouts for their admin views. The foundation must be built first.
**Independent Test**: Verify that a blank module can extend the master layout and display the standard sidebar and navbar properly.
**Acceptance Scenarios**:
1. **Given** a blade view, **When** I include a shared component (e.g., alert or modal), **Then** it renders correctly with the proper styling.

### User Story 2 - Settings Management (Priority: P2)
As a Super Admin, I want to manage general application settings so that I can configure the system globally without code changes.
**Why this priority**: Essential for application configuration (e.g., tax rates, social links).
**Independent Test**: Can be tested by navigating to settings in the admin panel, updating a value, and verifying it is saved and retrievable globally.
**Acceptance Scenarios**:
1. **Given** I am logged in as Super Admin, **When** I view settings, **Then** I see all available configuration options.
2. **Given** I update a setting, **When** I save, **Then** the value is updated in the database.

### User Story 3 - Public API Information Endpoints (Priority: P3)
As a Mobile Client/Driver, I want to retrieve static pages (terms, about, privacy) and global variables (tax, social links) so that I can display them in the app.
**Why this priority**: Required for app initialization and providing legal/informational pages to users.
**Independent Test**: Call the API endpoints and ensure correct localized data is returned.
**Acceptance Scenarios**:
1. **Given** no authentication, **When** I request terms with `Accept-Language: ar`, **Then** I receive the Arabic version of the terms.
2. **Given** no authentication, **When** I request tax info, **Then** I receive the correct tax percentage configured in settings.

### Edge Cases

- What happens when a requested setting key does not exist?
- What happens if a non-Super Admin user attempts to access the settings endpoints?
- How does the system handle missing translations for static pages if only one language is seeded?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST provide a `Setting` model and database table with `key`, `display`, `value` (nullable), and `type` columns.
- **FR-002**: System MUST restrict Settings management strictly to the "Super Admin" role.
- **FR-003**: System MUST provide API endpoints for retrieving terms, about, privacy, social links, and tax information.
- **FR-004**: System MUST encapsulate logic for saving settings, fetching logs, and removing images within a dedicated service layer.
- **FR-005**: System MUST seed initial data for terms (ar/en), privacy (ar/en), about, social links, and tax.
- **FR-006**: System MUST provide shared Blade layouts (`master`), includes (`navbar`, `footer`, `sidebar`, `js`, `css`), and reusable UI components.
- **FR-007**: System MUST provide shared utility functions (Response, Uploader, FCM, SMS) that are globally accessible by other modules.

### Key Entities

- **Setting**: Represents global application configuration (key, display, value, type).

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: All global settings can be successfully managed via the Admin interface.
- **SC-002**: API clients can retrieve all static information (terms, tax, etc.) successfully.
- **SC-003**: Other modules can successfully load the shared Blade layouts and assets without code duplication.
- **SC-004**: All utility helpers (Response, Uploader, FCM) are functional and accessible across the application.

## Assumptions

- "Super Admin" role will be predefined in the system (likely by the Admin module seeder).
- Assets (CSS/JS) for the Bootstrap 5 template will be placed in the `public/` directory as per the Constitution.
- The `type` column in Settings is used to determine the input field type (text, file, etc.) in the admin panel UI.
