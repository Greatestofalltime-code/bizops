# BizOps

BizOps is a multi-tenant business management platform designed to allow multiple companies to operate independently on a shared system while maintaining strict data isolation.

## Features
- Multi-tenant architecture
- Company and user management
- Role-based access control
- Activity logging
- Document management

## Tech Stack
- PHP (Vanilla)
- MySQL
- HTML, CSS, JavaScript

## Project Status
🚧 In development

## Core Architecture (MVP Foundation)

- Multi-tenant architecture (tenant-based data isolation)
- Role-based access control (RBAC)
- Secure authentication & session handling
- Token-based password reset with expiry
- Middleware-driven access enforcement
- Modular MVC structure

This repository currently focuses on backend architecture and system flows. UI/UX enhancements will be introduced after MVP validation.

## Core Architecture (MVP Foundation)

- Multi-tenant architecture (tenant-based data isolation)
- Role-based access control (RBAC)
- Secure authentication & session handling
- Token-based password reset with expiry
- Middleware-driven access enforcement
- Modular MVC structure

This repository currently focuses on backend architecture and system flows. UI/UX enhancements will be introduced after MVP validation.

## Authentication Flow

- Company registration creates:
  - Tenant
  - Company Admin user
  - Admin role
- Secure login with password hashing
- Session-based authentication
- Middleware enforcement for protected routes
- Secure logout

## Tech Stack

- PHP (Vanilla, MVC architecture)
- MySQL
- PDO
- HTML (minimal, MVP)
- Git & GitHub

## Roadmap

- User management module
- Activity logging
- Document management
- Notifications system
- UI/UX enhancement
- AI-powered assistant (future phase)
