# WEB-CTF (College Challenge)

Multi-level web security CTF built with Docker Compose.

## Overview

This project contains 4 progressive web challenges:

- Warmup (`web-warmup`): classic SQL injection login bypass
- Easy (`web-easy`): reflected XSS chain
- Medium (`web-medium`): time-based blind SQL injection
- Hard (`web-hard` + `web-hard-internal`): JWT confusion -> SSRF -> XXE -> PHP eval bypass chain

The stack also includes a shared MySQL database for Warmup/Medium.

## Project Structure

- `docker-compose.yml`: starts all services
- `web-warmup/`: warmup PHP app + SQL initialization files
- `web-easy/`: easy challenge PHP app
- `web-medium/`: medium challenge PHP app
- `web-hard/`: hard public app (JWT/auth/admin tools)
- `web-hard-internal/`: internal-only service used in hard challenge
- `Solution/`: optional helper scripts and notes

## Prerequisites

Install these first:

- Docker
- Docker Compose (plugin `docker compose` or standalone `docker-compose`)

Verify:

```bash
docker --version
docker compose version
```

## Setup and Run

From the project root:

```bash
# clone and enter this repository directory
cd WEB-CTF
docker compose up --build -d
```

If your machine only supports legacy compose:

```bash
docker-compose up --build -d
```

Check status:

```bash
docker compose ps
```

Check logs:

```bash
docker compose logs -f
```

## Challenge URLs

Open in browser:

- Warmup: http://localhost:8080
- Easy: http://localhost:8081
- Medium: http://localhost:8082
- Hard: http://localhost:8083

Notes:

- `web-hard-internal` is intentionally not exposed publicly (reachable only from Docker network).
- Warmup and Medium use MySQL (`db` service) with:
  - host: `db`
  - user: `root`
  - password: `rootpass`
  - database: `warmupdb`

## Game Flow

Recommended level order:

1. Warmup -> get flag
2. Easy -> requires warmup flag as input on `index.php`
3. Medium -> requires easy flag to unlock
4. Hard -> requires medium flag to unlock

## Stop / Restart / Reset

Stop containers:

```bash
docker compose down
```

Stop and remove volumes/networks for a clean reset:

```bash
docker compose down -v
```

Rebuild from scratch:

```bash
docker compose up --build -d
```

## Troubleshooting

If services are not starting:

1. Check port conflicts on `8080`, `8081`, `8082`, `8083`.
2. Run `docker compose ps` and `docker compose logs -f`.
3. If database state seems broken, reset with `docker compose down -v` and start again.

## Optional: Solution Scripts

The `Solution/` directory contains helper scripts/notes used for solving practice paths (`CTF.py`, `forge.py`, `pass.py`).

If you want to run Python helpers:

```bash
cd Solution
python3 -m venv .venv
source .venv/bin/activate
pip install requests
```

Then run, for example:

```bash
python3 CTF.py
```

## Security Note

This repository intentionally contains vulnerable code for educational CTF use only.
Do not deploy this stack on public internet infrastructure.
