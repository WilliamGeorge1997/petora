# Feature Specification: Clinic Module

**Feature Branch**: `005-clinic-module`

**Created**: 2026-08-26

**Status**: Draft

**Input**: User description: "using nwidart commands and our default store model generate the new module clinic with similar properties of store"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Create and Manage Clinics (Priority: P1)

Super Admins and Managers need to be able to create, view, edit, and delete clinics within the admin dashboard so that veterinary clinic data can be maintained.

**Why this priority**: Core CRUD operations are necessary for the entity to exist and be managed.

**Independent Test**: Can be fully tested by creating a new Clinic via the Admin Panel and verifying its presence in the database and API.

**Acceptance Scenarios**:

1. **Given** an admin is logged in, **When** they submit the Create Clinic form with valid bilingual data, **Then** the clinic is saved and displayed in the list.
2. **Given** an existing clinic, **When** an admin updates its image and description, **Then** the changes are persisted.

---

### User Story 2 - API Access for Clients (Priority: P2)

Clients using the Flutter app need to be able to fetch a list of active clinics so they can view available veterinary services.

**Why this priority**: End users must be able to consume the created data.

**Independent Test**: Can be tested by hitting the GET `/api/clinic` endpoint and verifying the JSON response.

**Acceptance Scenarios**:

1. **Given** a client requests the clinics list, **When** the API is called, **Then** only `is_active=true` clinics are returned with their translated names matching the `Accept-Language` header.

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST provide a fully functional CRUD module for Clinics.
- **FR-002**: System MUST define the `Clinic` entity using properties similar to the `Store` entity.
- **FR-003**: System MUST support Arabic and English translations for text-based attributes (title, description, address).
- **FR-004**: System MUST expose an API endpoint to list active clinics.
- **FR-005**: System MUST allow uploading and updating a clinic image.
- **FR-006**: Clinic structure MUST strictly adhere to the locked canonical structure of the `Store` module (as defined in the constitution).
- **FR-007**: System MUST treat Clinic as a standalone entity (it does NOT belong to a Company).

### Key Entities 

- **Clinic**: Represents a veterinary clinic.
  - Attributes: `title` (json), `description` (json), `address` (json), `phone` (string), `image` (string), `lat` (string), `long` (string), `is_active` (boolean).
  - Relationships: Standalone entity.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Admins can successfully create a Clinic with all required fields in under 2 minutes via the dashboard.
- **SC-002**: The API endpoint for clinics returns data in under 200ms.
- **SC-003**: The module adheres 100% to the canonical `Store` structure.

## Assumptions

- We assume the same permissions generation logic applies (e.g. `Index-clinic`, `Create-clinic`).
- We assume the Clinic uses Pattern A for images (single image column) just like the Store.
