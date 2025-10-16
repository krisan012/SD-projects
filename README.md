# Project Management System

A Laravel-based project management application with task tracking, user authentication, and progress monitoring.

## Features

- **User Authentication**: Secure login/registration with Laravel Breeze & Sanctum
- **Project Management**: Create, update, and delete projects
- **Task Management**: Add, update, and delete tasks within projects
- **Progress Tracking**: Visual progress bars showing task completion
- **Authorization**: Users can only access their own projects
- **API Endpoints**: RESTful API for frontend integration
- **Testing**: included Pest test suite

## Tech Stack

- **Backend**: Laravel 12
- **Authentication**: Laravel Breeze + Sanctum
- **Frontend**: Blade templates with Alpine.js
- **Styling**: Tailwind CSS
- **Testing**: Pest PHP
- **Database**: MySQL

## Prerequisites

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL
- Git

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd SD-projects
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Update your `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

## Database Setup

1. **Run migrations**
   ```bash
   php artisan migrate
   ```

2. **Seed the database**
   ```bash
   php artisan db:seed
   ```

## Running the Application

1. **Start the Laravel development server**
   ```bash
   php artisan serve
   ```
   The application will be available at `http://localhost:8000`

2. **Build frontend assets (if needed)**
   ```bash
   npm run dev
   # or for production
   npm run build
   ```

## Default Credentials

After seeding the database, you can log in with these credentials:

### Admin User
- **Email**: `admin@example.com`
- **Password**: `password`

### Regular User
- **Email**: `user@example.com`
- **Password**: `password`

## API Endpoints

### Authentication Required
All API endpoints require Sanctum authentication.

#### Projects
- `POST /api/project` - Create a new project
- `POST /api/project/update/{project}` - Update a project
- `POST /api/project/delete/{project}` - Delete a project

#### Example API Usage
```javascript
// Get CSRF cookie first
await axios.get('/sanctum/csrf-cookie');

// Create project
const response = await axios.post('/api/project', {
    title: 'My Project',
    description: 'Project description',
    deadline: '2024-12-31',
    tasks: [
        {
            title: 'Task 1',
            status: 'todo',
            due_date: '2024-12-15'
        }
    ]
});
```

## Testing

Run the comprehensive test suite:

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ProjectManagementTest.php

# Run with coverage
php artisan test --coverage
```

### Test Coverage
- ✅ Project creation and validation
- ✅ Task management (create, update, delete)
- ✅ Authentication protection
- ✅ Authorization (users can only access their own projects)
- ✅ API endpoint testing

## Project Structure

```
app/
├── Http/Controllers/Api/     # API controllers
├── Models/                   # Eloquent models
├── Policies/                 # Authorization policies
└── Requests/                 # Form request validation

database/
├── factories/               # Model factories for testing
├── migrations/             # Database migrations
└── seeders/               # Database seeders

tests/
└── Feature/               # Feature tests with Pest
```

## Key Features Implementation

### Progress Tracking
- Visual progress bars showing task completion percentage
- Real-time updates when task status changes
- Task counters (e.g., "3 of 5 tasks completed")

### Authorization
- Users can only view/edit their own projects
- Policy-based authorization for delete operations
- Sanctum middleware for API protection

### Task Management
- Create tasks with title, status, and due date
- Update existing tasks
- Delete tasks from projects
- Status options: todo, in_progress, done
- Assign Project to other user

### Code Style
- Follow PSR-12 coding standards
- Use meaningful variable and method names
- Add comprehensive tests for new features
- Document API endpoints

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).