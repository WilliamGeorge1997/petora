# Feature Specification: Company Module

**Feature Branch**: `[003-company-module]`

**Created**: 2026-08-25

**Status**: Draft

**Input**: User description: "Build company module json title, bool is active, string phone nullable, address json nullable, model have logoptions, scope active, controller crud operation, service findall findbyid, findby, save, update, delete, activate, custom request, dto, 3 views, index, create, edit use nwidart commands"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Admin Manages Company Catalog (Priority: P1)

As a system administrator, I want to manage the catalog of companies (create, view, update, delete) so that I can maintain the list of business entities operating on the platform.

**Why this priority**: Managing companies is the foundational feature of the Company Module, allowing the business to onboard and update partner companies.

**Independent Test**: Can be fully tested by navigating to the Companies section in the admin dashboard and successfully creating, viewing, and modifying a company record.

**Acceptance Scenarios**:

1. **Given** I am logged into the admin dashboard, **When** I fill out the new company form with a title (in multiple languages), phone, and address, **Then** the company is saved and appears in the company list.
2. **Given** a company exists, **When** I edit its details and save, **Then** the updated information is reflected in the system.
3. **Given** a company exists, **When** I choose to delete it, **Then** it is permanently removed from the system.

---

### User Story 2 - Toggle Company Active Status (Priority: P2)

As a system administrator, I want to activate or deactivate a company so that I can temporarily suspend or reinstate a company's visibility and operations on the platform without deleting their data.

**Why this priority**: Businesses often need to be suspended for administrative reasons (e.g., unpaid bills, contract negotiation) while retaining their historical data.

**Independent Test**: Can be fully tested by clicking a toggle/button on the company list to change its status, then verifying the company is excluded from active listings.

**Acceptance Scenarios**:

1. **Given** an active company, **When** I click to deactivate it, **Then** its status changes to inactive and it no longer appears in customer-facing active company lists.
2. **Given** an inactive company, **When** I click to activate it, **Then** its status changes to active and it becomes visible again.

---

### Edge Cases

- What happens when an admin attempts to create a company with a very long title or address?
- How does the system handle missing optional fields (phone, address) during creation or update?
- What happens if an admin tries to delete a company that has associated stores or products? (Assumption: This will be handled by standard relational constraints or future business logic).

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST allow administrators to create, read, update, and delete company records.
- **FR-002**: System MUST capture the company's title in multiple languages (Arabic and English).
- **FR-003**: System MUST optionally capture the company's phone number and multi-lingual address.
- **FR-004**: System MUST allow administrators to toggle the active status of a company.
- **FR-005**: System MUST maintain an audit log of all changes made to company records (tracking creations, updates, and deletions).
- **FR-006**: System MUST provide a filtered view that only returns active companies for use by client-facing applications.

### Key Entities

- **Company**: Represents a business entity operating on the platform. Contains bilingual titles, active status, optional phone, and optional bilingual address.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Administrators can successfully create a new company record in under 1 minute.
- **SC-002**: The admin dashboard successfully lists companies with pagination, loading in under 1 second.
- **SC-003**: Audit logs accurately reflect 100% of state-changing operations on company records.

## Assumptions

- Target users (Administrators) have appropriate permissions to manage companies.
- The system will reuse the existing admin dashboard layout and authentication mechanisms.
- Deleting a company will be a hard delete as per standard project practices.
- Companies are top-level entities, and their relationships (like owning stores) will be managed from the child entity's side or in future features.
