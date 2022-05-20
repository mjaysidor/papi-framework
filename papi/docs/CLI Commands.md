## Overview:
CLI Commands module is accessed via calling **"php cli"** from project root. It lists and describes all the available commands. To call a specific command use **"php cli group:command"** (ex. "php cli db:drop").

## Available commands:

### Database
* **db:create** Creates the database specified in DatabaseConfig class
* **db:drop** Drops the database specified in DatabaseConfig class
### Auth
* **auth:create** Creates authentication system (user validation & JWT mechanisms + optionally user resource)
### Migrations
* **migration:make** Generates migrations based on differences between code (PHP Resource classes) and current database schema
* **migration:execute** Executes unexecuted migrations
### Make
* **make:relation** Creates a relation between resources
* **make:controller** Creates a plain controller
* **make:resource** Creates a REST resource with a CRUD controller
### Cache
* **cache:clear** Clears database query result cache   
