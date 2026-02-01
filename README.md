# Keploy PHP + PostgreSQL Quickstart

This repository demonstrates how to use **Keploy** with a **PHP REST API** backed
by **PostgreSQL**, without adding SDKs or manual instrumentation.

It shows how Keploy can record and replay real HTTP traffic for the PHP app
using Docker (works well in GitHub Codespaces).

---

## What this example does

- Simple PHP REST API (Apache + pgsql)
- PostgreSQL used for persistent storage
- REST endpoints:
  - `GET /index.php/health`
  - `POST /index.php/users`
  - `GET /index.php/users`
  - `GET /index.php/users/{id}`
  - `DELETE /index.php/users/{id}`
- Docker Compose setup for app + db
- Keploy record and replay working end-to-end

---

## Tech stack

- Language: PHP 8.2 (Apache)
- DB: PostgreSQL 15
- Containerization: Docker & Docker Compose
- Testing: Keploy (record & replay)

---

## Project structure

```
.
├── app/
│   ├── index.php
│   ├── db.php
│   └── migrate.sql
├── Dockerfile
├── docker-compose.yml
├── keploy.yml
└── README.md
```

---

## Prerequisites

- Docker & Docker Compose
- (Optional local run) PHP >= 8.0 with `pgsql`/`pdo_pgsql`, and PostgreSQL

---

## Run with Docker

Create Docker network (one-time):

```bash
docker network create keploy-network || true
```

Build and start the app + DB:

```bash
docker compose up --build
```

App will be available on `http://localhost:8080/index.php`.

---

## Test the API

Health:

```bash
curl http://localhost:8080/index.php/health
# -> {"status":"ok"}
```

Create user:

```bash
curl -X POST http://localhost:8080/index.php/users \
  -H 'Content-Type: application/json' \
  -d '{"name":"Alice","email":"alice@example.com"}'
```

List users:

```bash
curl http://localhost:8080/index.php/users
```

Get / Delete by id use `/index.php/users/{id}`.

---

## Using Keploy

> Note: Keploy record/replay uses eBPF and is supported on Linux. This
> quickstart was tested in GitHub Codespaces (Linux-based).

### Install Keploy (example)

```bash
curl --silent -O -L https://keploy.io/install.sh
source install.sh
keploy --version
```

### Record traffic (local / OSS)

Start recording while bringing up the app. The compose service name is `app`.

```bash
keploy record -c "docker compose up --build" \
  --container-name app \
  --buildDelay 30
```

In another terminal (or the Codespaces terminal), generate traffic:

```bash
curl http://localhost:8080/index.php/health
curl -X POST http://localhost:8080/index.php/users -H 'Content-Type: application/json' -d '{"name":"Alice","email":"alice@example.com"}'
curl http://localhost:8080/index.php/users
```

Stop the recording with Ctrl+C. Keploy will generate test artifacts in a
`keploy/` directory (recordings, test-sets, mocks).

### Replay tests

Run recorded tests against the service:

```bash
keploy test -c "docker compose up" \
  --container-name app \
  --delay 10 \
  --buildDelay 30
```

Expected: recorded test cases are replayed deterministically and pass.

---

## Notes

- The `db` service initializes the `users` table using `app/migrate.sql`.
- `docker-compose.yml` expects an external network `keploy-network` so Keploy
  containers can attach to the same network for transparent recording.
- Mask secrets before committing recordings to git.

---

## Feedback

If you'd like, I can add a `devcontainer` for Codespaces that pre-installs the
Keploy CLI and helper scripts (`scripts/record.sh`, `scripts/replay.sh`).
