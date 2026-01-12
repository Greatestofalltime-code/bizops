# BizOps Database Schema Documentation

## Overview
The BizOps database is designed to support a **multi-tenant SaaS architecture**, where multiple independent companies (tenants) use the same application while keeping their data strictly isolated.

Each company is represented as a **tenant**, and all company-owned data is linked using a `tenant_id`.

---

## Core Design Principles

- One database, shared schema
- Strong tenant isolation using `tenant_id`
- Role-based access control
- Immutable audit logs
- Scalable and extensible for future modules

---

## Tables and Their Purpose

### 1. tenants
Stores all registered companies on the platform.

**Purpose:**
- Represents a company using BizOps
- Acts as the root owner of all company-related data

**Key Fields:**
- `id` – Unique identifier for the company
- `name` – Company name
- `logo` – Company logo (optional)
- `status` – Active or suspended
- `created_at` – Company creation date

---

### 2. users
Stores users belonging to different companies.

**Purpose:**
- Manages authentication and identity
- Associates users with a specific company

**Key Fields:**
- `id` – Unique user ID
- `tenant_id` – Company the user belongs to
- `full_name` – User’s name
- `email` – User’s email (unique per company)
- `password` – Hashed password
- `status` – Active or inactive
- `created_at` – Account creation date

**Relationship:**
- Many users belong to one tenant

---

### 3. roles
Defines roles within a company.

**Purpose:**
- Controls what users can access or perform
- Roles are scoped per company

**Key Fields:**
- `id` – Role ID
- `tenant_id` – Company the role belongs to
- `name` – Role name (e.g. Company Admin, Staff)

**Relationship:**
- Many roles belong to one tenant

---

### 4. user_roles
Maps users to their assigned roles.

**Purpose:**
- Supports many-to-many relationship between users and roles

**Key Fields:**
- `user_id` – User ID
- `role_id` – Role ID

**Relationship:**
- A user can have multiple roles
- A role can be assigned to multiple users

---

### 5. documents
Stores metadata for uploaded documents.

**Purpose:**
- Enables document management per company
- Tracks file ownership and uploader

**Key Fields:**
- `id` – Document ID
- `tenant_id` – Owning company
- `uploaded_by` – User who uploaded the document
- `filename` – Original file name
- `filepath` – File storage path
- `created_at` – Upload date

**Note:**
Actual files are stored on disk; the database stores metadata only.

---

### 6. activity_logs
Tracks user actions within the system.

**Purpose:**
- Provides an audit trail
- Improves security and accountability

**Key Fields:**
- `id` – Log ID
- `tenant_id` – Company context
- `user_id` – User who performed the action
- `action` – Description of the activity
- `created_at` – Timestamp

**Important Rule:**
Activity logs are immutable and never deleted.

---

### 7. notifications
Stores in-app notifications for users.

**Purpose:**
- Delivers system and activity-based alerts

**Key Fields:**
- `id` – Notification ID
- `tenant_id` – Company context
- `user_id` – Recipient user
- `message` – Notification content
- `is_read` – Read status
- `created_at` – Notification timestamp

---

### 8. super_admins
Stores platform-level administrators.

**Purpose:**
- Manages the entire BizOps platform
- Operates outside tenant scope

**Key Fields:**
- `id` – Admin ID
- `full_name` – Admin name
- `email` – Unique admin email
- `password` – Hashed password
- `created_at` – Account creation date

**Note:**
Super admins do not belong to any tenant.

---

## Entity Relationships (High-Level)

- One tenant has many users
- One tenant has many roles
- Users can have multiple roles
- One tenant owns many documents
- One tenant owns many activity logs
- One tenant owns many notifications

---

## Tenant Isolation Rule

> Every company-owned table contains a `tenant_id`.

All database queries must be scoped using this field to prevent data leakage between companies.

---

## Future Extensions

This schema is designed to support future modules such as:
- Inventory management
- Sales and billing
- AI assistant features
- Mobile application access

---

**Document Version:** 1.0  
**Project:** BizOps – Multi-Tenant Business Management Platform

