# Project Improvement Plan

## 1. Client-Project File Attachments

Allow files to be uploaded and associated directly with a client-project. Supported formats should include `.docx`, `.xlsx`, `.pdf`, and image files (`.png`, `.jpg`, etc.). This enables project analysis documents, research exports, and visual references to be stored alongside the project record rather than managed externally.

---

## 2. Printable Invoice Styling

Invoices currently lack a print-ready layout. A dedicated PDF invoice template should be designed and applied when exporting — covering the standard invoice structure: header with logo and company details, client information block, itemized table with quantities and unit prices, subtotal/tax/total summary, and a footer with payment terms and notes. The goal is a professional, client-facing document that can be handed off or emailed directly.

---

## 3. Client-Project Cancellation Status

Add a `canceled` option to the project status field. When selected, a required **cancellation reason** field should appear, capturing why the project was canceled. This ensures canceled projects remain traceable and informative rather than just archived.

---

## 4. Testimonial: Email Field

Add an **email address** field to the testimonial form. This allows verification of the testimonial's source and provides a contact point if follow-up is needed.

---

## 5. Searchable Project Type Select with Free-Text Fallback

Replace the current project type input with a **searchable select list**. The list should support live filtering as the user types. An **"Other"** option at the end of the list should reveal a free-text input, allowing custom project types to be entered when no existing option applies.

---

## 6. Audit Log Table

Introduce an `audit_log` table to track key system actions. At minimum, the following events should be logged:

- Adding a new client-project
- Editing a client-project
- Changing a project's status (including cancellations)
- Adding or editing an invoice
- Adding or editing a testimonial

Each log entry should capture: the action type, the affected record and its ID, the user who performed the action, and a timestamp.
