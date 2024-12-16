
# Dexwin Todo App API

A Laravel-based CRUD API for managing todos. This project includes full API documentation, end-to-end tests, and deployment.

---

## Features

- CRUD functionality for todos.
- Filter, search, and sort todos by status, title, or details.
- Pagination support for listing todos.
- Hosted API documentation and live API endpoint.
- Comprehensive tests with high code coverage.

---

## Hosted Links

- **API Endpoint**: [https://dexwin.kennedyowusu.com/api/v1/todos](https://dexwin.kennedyowusu.com/api/v1/todos)
- **API Documentation**: [https://dexwin.kennedyowusu.com/public/docs/](https://dexwin.kennedyowusu.com/public/docs/)

---

## Setup Instructions

### Prerequisites

Before setting up the project, ensure you have the following installed:

- PHP 8.1 or higher
- Composer
- A database (MySQL, SQLite, PostgreSQL, etc.)
- Node.js (optional for frontend tools or additional assets)

---

### Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/kennedyowusu/dexwin-todo-app-api.git
   cd dexwin-todo-app-api
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Set up environment variables**:
   Copy the `.env.example` file to `.env` and update the necessary database credentials:
   ```bash
   cp .env.example .env
   ```

4. **Generate the application key**:
   ```bash
   php artisan key:generate
   ```

5. **Run database migrations and seed the database**:
   ```bash
   php artisan migrate --seed
   ```

6. **Serve the application**:
   ```bash
   php artisan serve
   ```

By default, the application will run at `http://localhost:8000`.

---

## Usage

### Available Endpoints

| Method | Endpoint                   | Description                          |
|--------|----------------------------|--------------------------------------|
| GET    | `/api/v1/todos`            | List all todos                      |
| POST   | `/api/v1/todos`            | Create a new todo                   |
| GET    | `/api/v1/todos/{id}`       | Show a specific todo                |
| PUT    | `/api/v1/todos/{id}`       | Update a specific todo              |
| DELETE | `/api/v1/todos/{id}`       | Delete a specific todo              |

Refer to the **API Documentation** for detailed descriptions, request parameters, and example responses:
[API Documentation](https://dexwin.kennedyowusu.com/public/docs/)

---

## Testing

This project includes a comprehensive test suite to ensure functionality. To run tests:

1. Ensure your `.env` file is set up for testing with a SQLite database:
   ```env
   DB_CONNECTION=mysql
   DB_DATABASE=:memory:
   ```

2. Run the test suite:
   ```bash
   php artisan test
   ```

3. Generate a coverage report (optional, requires Xdebug):
   ```bash
   php artisan test --coverage
   ```

---

## API Documentation

The API documentation is automatically generated using Scribe and hosted online.

- **Hosted Documentation**: [https://dexwin.kennedyowusu.com/public/docs/](https://dexwin.kennedyowusu.com/public/docs/)

To regenerate the documentation locally:
```bash
php artisan scribe:generate
```

---

## Contributing

Contributions are welcome! If you encounter any issues or have ideas for improvement:

1. Fork the repository.
2. Create a new branch for your feature or bugfix:
   ```bash
   git checkout -b feature/your-feature-name
   ```
3. Submit a pull request with a detailed explanation.

---

## License

This project is licensed under the MIT License. Feel free to use, modify, and distribute this project as per the license terms.

---

**Author**: Kennedy Owusu
GitHub: [kennedyowusu](https://github.com/kennedyowusu)
API Documentation: [dexwin.kennedyowusu.com](https://dexwin.kennedyowusu.com/public/docs/)

