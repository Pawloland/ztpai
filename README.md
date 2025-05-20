# BILETRON

## Description

This fullstack application is a system for selling tickets, creating screenings, clients, workers and orders.

# Stack:

- PHP 8.4
- Symfony 7.2
- Doctrine ORM 3.3
- API Platform
- TypeScript
- React 19
- React Router 7.5.2
- PostgresSQL 17.4
- RabbitMQ 4.1.0

I chose this stack because it is a good combination of technologies that are widely used in the industry and have a large community support.

## Remarks 

It is best to work under Docker in a Linux environment, or with WSL2 (using a folder under a Linux file system such as ext4, not the Windows NTFS).
That way, live reload (HMR) in Vite works, it's faster, and there are fewer file permission issues.
Also for file upload to work correctly linux runtime is needed, because there is UID and GID impersonation taking place
within php service, when saving to a docker mapped volume (which is assumed to be a filesystem supporting linux permissions)
as a specified user from docker host, not a default user from docker guest container (83, www-data).
Right now it is set to 1000:1000 in docker-compose.yaml environment tag for php service.

## Getting started

### Prerequisites

- Docker
- Docker Compose
- Linux or WSL2 or at least a filesystem that supports Linux permissions (ext4, not NTFS) mounted to php service in docker-compose.yaml

### Nice to have

- PHP Storm

## Setup

Bellow instructions assume that docker is installed on linux host, hence the `$(id -u):$(id -g)` command to run commands
as the same user as the host user (so there are no permission issues with mounted volumes).
Follow them in the same order, and type them from inside the root of cloned repo  (the same dir where docker-compose.yaml is located).

Installing backend dependencies:

```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php composer install
```

Installing frontend dependencies:

```bash
docker compose up node-install
```

Starting php backend server in development mode and all other necessary services:

```bash
docker compose up -d
```

Starting vite frontend server in development mode:

```bash
docker compose up node-dev
```
# Architecture 

The app is split into 3 parts, DB, backend and frontend.
- DB is a PostgresSQL database, which is used to store all the data.
- Backend is a PHP Symfony application, which is used to handle all the business logic and API.
- Frontend is a React application, which is used to handle all the user interface and user experience.


There is also RabbitMQ service, which is used to handle all the async tasks, 
like orchestrating sending verification emails.

The flow is like this:
1. User interacts by the frontend (React app).
2. Frontend sends a request to the backend (Symfony app).
3. Backend handles the request and sends a response to the frontend and some async tasks to RabbitMQ.
4. There is one specific php consumer, which sends mails, and another one, which sets email as validated in the db, when a user verifies it.
5. Backend handles auth using HTTP Only cookies for workers and client, in separate cookies.
6. Sessions are stored in db and are automatically cleaned up after expiration. The backend is stateless, DB is the only source of truth.
7. Authorization is handled by Symfony security, using voters and roles, which are defined per worker type in DB.

## If you want to develop this project more, there are some usefully commands:

Clearing backend cache:

```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console cache:clear
```

Creating new controller template:

```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console make:controller SomeController
```

Creating new model template (entities):

```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console make:entity
```

Generating frontend structure with vite:

```bash
docker compose run --rm -it node-base npm create vite@latest
```

Adding new frontend dependency (e.g. react-router):

```bash
docker compose run --rm -it node-base npm install react-router
```

Validating the mapping against the database:

```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console doctrine:schema:validate -v
```

Verifying if mapping is correct:

```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console doctrine:mapping:info
```

Checking the differences between the database and the mapping:

```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console doctrine:schema:update --dump-sql
```

Showing the full SQL to create the database based on the mapping:

```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console doctrine:schema:create --dump-sql
```

Clear cache metadata:

```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console doctrine:cache:clear-metadata
```

Adding new processor for api-platform:

```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console make:state-processor
```

Running command in already running container

```bash
docker compose exec -it -u "$(id -u):$(id -g)" php bash
```

Creating new voter in symfony:

```bash
docker compose exec -it -u "$(id -u):$(id -g)" php php bin/console make:voter
```
