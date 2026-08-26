# Quickstart Validation: Clinic Module

This guide details how to validate the `Clinic` module end-to-end after implementation.

## Prerequisites

- Module generated and migrations run.
- Admin user exists and can log into the dashboard.
- At least one active `Clinic` record exists.
- Flutter API tester (e.g., Postman) configured with a valid Bearer token.

## 1. Verify Admin Dashboard CRUD

1. Log into the Petora Admin Dashboard.
2. Navigate to the **Clinics** section in the sidebar.
3. Click **Create Clinic** and fill out the form (Title en/ar, Description en/ar, Address en/ar, Phone, lat, long, Image).
4. Save the form.
5. **Expected Outcome**: You are redirected to the Clinics index page with a success alert. The new clinic appears in the table.

## 2. Verify Client API

1. Open Postman or your API client.
2. Send a `GET` request to `/api/clinic/clinics` (or the equivalent module endpoint).
   - Headers: `Accept: application/json`, `Accept-Language: en`, `Authorization: Bearer <token>`
3. **Expected Outcome**: You receive a `200 OK` response with `status: true` and the list of active clinics in the `data` payload matching the `contracts/api.md` schema.

## 3. Verify Active/Inactive Scope

1. In the Admin Dashboard, edit a Clinic and uncheck the **Active** checkbox. Save.
2. Send the same `GET /api/clinic` request from step 2.
3. **Expected Outcome**: The deactivated clinic does not appear in the API response.
