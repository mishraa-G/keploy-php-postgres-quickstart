# Keploy PHP + PostgreSQL Quickstart

This repository is a **beginner-friendly PHP + PostgreSQL quickstart**
demonstrating how to use **Keploy** to record and replay API traffic
without adding SDKs or modifying application code.

The example application is a simple **Users CRUD REST API**.

---

## Architecture Overview

- PHP 8.2 (Apache)
- PostgreSQL 15
- Docker Compose
- Keploy (network-based recording & replay)

---

## API Endpoints

| Method | Endpoint | Description |
|------|---------|-------------|
| GET | `/index.php/health` | Health check |
| POST | `/index.php/users` | Create a user |
| GET | `/index.php/users` | List users |
| GET | `/index.php/users/{id}` | Get user by ID |
| DELETE | `/index.php/users/{id}` | Delete user |

---

## Prerequisites

### For local (non-Docker) setup
- PHP >= 8.0
- PostgreSQL running locally
- PHP PostgreSQL extensions (`pgsql`, `pdo_pgsql`)

### For Docker setup
- Docker
- Docker Compose

### For Keploy
- Keploy CLI installed  
  https://keploy.io/docs/installation

---

## Local Setup (Without Docker)

1. Create a PostgreSQL database:
```sql
CREATE DATABASE keploy;
