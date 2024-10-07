# LOTR CRUD Project - Api coding task

<div align="center">
  <img src="public/you-shall-not-pass-lotr.gif" alt="LOTR Ring" />
</div>
**A comprehensive CRUD application based on The Lord of the Rings universe, developed using Symfony and Domain-Driven Design (DDD). The project incorporates API documentation with AsyncAPI and OpenAPI, testing tools, and Dockerized environments.**

## Table of Contents

- [Project Overview](#project-overview)
- [Installation](#installation)
- [Usage](#usage)
  - [Docker Setup](#docker-setup)
  - [Async Events](#async-events)
  - [Available Makefile Commands](#available-makefile-commands)
- [Documentation](#documentation)
  - [OpenAPI Documentation](#openapi-documentation)
  - [AsyncAPI Documentation](#asyncapi-documentation)
- [Testing & Code Quality](#testing--code-quality)
- [Contributing](#contributing)
- [License](#license)

---

## Project Overview

This project is a **CRUD application** built using **Symfony** and follows **Domain-Driven Design (DDD)** principles. It models key elements from *The Lord of the Rings* universe, including characters, factions and equipment. The application is Dockerized and supports both **OpenAPI** and **AsyncAPI** specifications for documentation.

---

## Installation

Follow the steps below to set up the project:

1. **Clone the repository**:
    ```bash
    git clone https://github.com/ivanmunozruiz/api-coding-task.git
    cd api-coding-task
    ```

2. **Build and start the Docker containers**:
    ```bash
    make build
    ```

3. **Install dependencies using Composer**:
    ```bash
    make composer-install
    ```

4. **Run database migrations**:
    ```bash
    make up
    ```

---

## Usage

### Docker Setup

To start the application, use the following command:

```bash
make up
```

This will start all the Docker containers in the background and apply the necessary database migrations.

To stop the containers:

```bash
make stop
```

### Async Events 

[RabbitMQ Management Panel](http://localhost:15672/)

The project also includes RabbitMQ as queue manager for asynchronous events.
To see rabbitmq management panel, visit [RabbitMQ Management Panel](http://localhost:15672/) (With the Docker containers running).

```bash
user: guest
password: guest
```


- **Consume Async events**: 
    ```bash
    make consume-async-events
    ```

---

### Available Makefile Commands

Below is a list of available commands in the `Makefile`. You can use `make <command>` to run them:

| Command                    | Description                                          |
|----------------------------|------------------------------------------------------|
| `make build`               | Build the Docker containers and install dependencies |
| `make composer-install`    | Install PHP dependencies via Composer                |
| `make composer-update`     | Update Composer dependencies                         |
| `make enter-container-php` | Enter the PHP container                              |
| `make asyncapi-resolve`    | Generate AsyncAPI documentation                      |
| `make openapi-resolve`     | Generate OpenAPI documentation                       |
| `make php-lint`            | Run PHP code linter                                  |
| `make phpstan`             | Run PHPStan static analysis                          |
| `make rector`              | Apply code improvements using Rector                 |
| `make unit-test`           | Run unit tests                                       |
| `make mutant-test`         | Run mutant tests                                     |
| `make bdd-test`            | Run Behat BDD tests                                  |
| `make up`                  | Start the Docker containers                          |
| `make stop`                | Stop the Docker containers                           |
| `make consume-async-events`| Start Consumer async events exchange consumer        |

For a full list of commands, run `make help`.

---

## Documentation

### OpenAPI Documentation [Swagger UI (Click here)](http://localhost:8081/) 

The project includes a fully documented API using OpenAPI. To generate or validate the OpenAPI spec:

- **Generate OpenAPI spec**: 
    ```bash
    make openapi-resolve
    ```
- **Validate OpenAPI spec**: 
    ```bash
    make openapi-lint
    ```

The documentation can be found in the `gen/openapi.yaml` file.
To see the documentation you can visit [Swagger Editor](http://localhost:8081/) (With the Docker containers running).

### AsyncAPI Documentation

The project also includes AsyncAPI documentation for asynchronous message-based interactions:

- **Generate AsyncAPI spec**: 
    ```bash
    make asyncapi-resolve
    ```
- **Validate AsyncAPI spec**: 
    ```bash
    make asyncapi-lint
    ```

The generated AsyncAPI documentation is located in `gen/asyncapi.yaml`. if you want to visualize it, copy yml content and paste it in [AsyncAPI Playground](https://playground.asyncapi.io/).

---

## Testing & Code Quality

We use several tools to ensure code quality and reliability:

- **Unit Testing**: Execute unit tests with PHPUnit:
    ```bash
    make unit-test
    ```

- **Mutation Testing**: Run mutation tests with Infection:
    ```bash
    make mutant-test
    ```

- **Behavior-Driven Development (BDD)**: Execute BDD tests with Behat:
    ```bash
    make bdd-test
    ```

- **Static Code Analysis**: Check code quality with PHPStan:
    ```bash
    make phpstan
    ```

- **Automatic Code Fixes**: Run Rector to refactor code automatically:
    ```bash
    make rector
    ```

---

## Contributing

Contributions are welcome! Please fork this repository and submit a pull request with your changes. Ensure that all tests pass and code follows the PSR standards.

---

## License

This project is licensed under the MIT License.
