# Grade Presenter

A modern, instructor-friendly web application for managing classrooms and presenting student grades in a professional, engaging slideshow format.

## Core Features

### 🔐 Authentication
- Secure login via **Laravel Breeze**
- Email verification for instructors
- Password reset functionality
- Profile management

### 📚 Classroom Management
- Create and organize classrooms by course name, section/block, and year
- View all classrooms with a quick overview of enrolled students
- Edit classroom details anytime
- Delete classrooms (with cascade delete for associated data)

### 👥 Student Management
- Add students to classrooms with detailed information:
  - Last Name, First Name, Middle Initial
  - Student ID
  - Course, Year, Block
- Track two grades per student:
  - **Laboratory Grade** (0–100)
  - **Lecture Grade** (0–100)
- Edit and update student information
- Automatically calculate final average: **(Lab + Lecture) ÷ 2**

### 🎬 Grade Slideshow (Standout Feature)
Present student grades in a professional, engaging slideshow:
- **Slide 1** - Student Info + Grade Breakdown
  - Course Name, Full Name, Student Details
  - Separate display boxes for Lab and Lecture grades
- **Slide 2** - Final Average
  - Prominent display of calculated average grade
- **Timing**: Automatic 15-second advance per slide (2 slides per student)
- **Controls**:
  - Previous/Next navigation for manual control
  - Pause/Resume toggle
  - Progress bar showing slide duration
  - Slide counter (e.g., "Student 5, Slide 1 of 2")
- **Design**: Dark-themed gradient backgrounds with glassmorphism UI

## Tech Stack

### Backend
- **Framework**: Laravel 12 (PHP 8.2+)
- **Database**: SQLite (configurable)
- **ORM**: Eloquent
- **Authentication**: Laravel Breeze
- **Testing**: Pest PHP with Laravel plugin

### Frontend
- **Styling**: TailwindCSS 3.1 + Forms Plugin
- **Build Tool**: Vite 7
- **Interactivity**: Alpine.js 3.4
- **HTTP Client**: Axios 1.1
- **Animations**: Vanilla JavaScript with requestAnimationFrame

### DevOps
- **CI/CD**: GitHub Actions (PHP 8.2, 8.3, 8.4)
- **Code Quality**: StyleCI with Laravel preset
- **Testing**: PHPUnit with Pest

## Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm (or yarn/bun)
- SQLite (included with PHP)

### Quick Start

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd grade-presenter/myapp
   ```

2. **Run the setup script**
   ```bash
   composer setup
   ```
   This will:
   - Install PHP dependencies via Composer
   - Generate the application key
   - Run database migrations
   - Build frontend assets

3. **Start the development server**
   ```bash
   composer run dev
   ```
   This runs:
   - PHP development server (http://localhost:8000)
   - Queue listener for background jobs
   - Log viewer
   - Vite dev server with hot reload

4. **Access the application**
   - Navigate to `http://localhost:8000`
   - Register as an instructor
   - Verify your email
   - Start creating classrooms!

### Manual Setup (Alternative)

```bash
# Install dependencies
composer install
npm install

# Generate app key
php artisan key:generate

# Create and migrate database
php artisan migrate

# Build assets
npm run build

# For development with hot reload
npm run dev

# In another terminal, start the server
php artisan serve

# Optional: start queue listener
php artisan queue:listen
```

## Usage

### 1. **Create a Classroom**
   - Click "New Classroom" from dashboard
   - Enter course name, code, year, and block
   - Save

### 2. **Add Students**
   - Go to classroom details
   - Click "Add Student"
   - Fill in student information and initial grades (0-100)
   - Save

### 3. **Update Grades**
   - Click on a student to edit
   - Update Laboratory and Lecture grades
   - Save changes

### 4. **Present Grades (Slideshow Mode)**
   - From classroom view, click "Start Slideshow"
   - Presentation will auto-advance every 15 seconds
   - Use Previous/Next buttons to navigate manually
   - Click Pause to hold on a slide
   - Watch the progress bar and slide counter

## Project Structure

```
myapp/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController
│   │   │   ├── ClassroomController
│   │   │   ├── StudentController
│   │   │   └── ProfileController
│   │   └── Requests/          # Form request validations
│   ├── Models/
│   │   ├── User
│   │   ├── Classroom
│   │   └── Student
│   └── Providers/
├── resources/
│   ├── views/
│   │   ├── auth/              # Authentication views
│   │   ├── classrooms/        # Classroom CRUD + slideshow
│   │   ├── students/          # Student forms
│   │   ├── profile/           # User settings
│   │   └── components/        # Reusable Blade components
│   ├── css/                   # TailwindCSS
│   └── js/                    # Alpine.js & app logic
├── routes/
│   ├── web.php                # Main routes
│   └── auth.php               # Auth routes
├── database/
│   ├── migrations/            # Database schema
│   ├── factories/             # Model factories for testing
│   └── seeders/               # Database seeders
├── tests/                     # Pest PHP tests
├── config/                    # Laravel configuration
└── public/                    # Web server root
```

## Database Schema

### Users Table
Authentication and instructor profiles
- id, name, email, password, email_verified_at, timestamps

### Classrooms Table
Course organization
- id, course_name, course_code, year, block, timestamps

### Students Table
Students enrolled in each classroom with grades
- id, classroom_id, student_id, last_name, first_name, middle_initial
- course, year, block, laboratory_grade, lecture_grade, timestamps
- *Note: Cascade delete on classroom_id*

## Routes Overview

### Public (Guest) Routes
- `GET /register` - User registration
- `GET /login` - Login page
- `POST /login` - Submit login
- `GET /forgot-password` - Password reset request
- `GET /verify-email` - Email verification page

### Protected (Authenticated) Routes
- `GET /` - Dashboard
- `GET /classrooms` - List all classrooms
- `GET /classrooms/create` - Create classroom form
- `POST /classrooms` - Store new classroom
- `GET /classrooms/{id}/edit` - Edit classroom form
- `PUT /classrooms/{id}` - Update classroom
- `DELETE /classrooms/{id}` - Delete classroom
- `GET /classrooms/{id}/students/create` - Add student form
- `POST /classrooms/{id}/students` - Store new student
- `GET /classrooms/{id}/students/{studentId}/edit` - Edit student form
- `PUT /classrooms/{id}/students/{studentId}` - Update student
- `DELETE /classrooms/{id}/students/{studentId}` - Delete student
- `GET /classrooms/{id}/slideshow` - **Grade presentation slideshow**
- `GET /profile` - Edit profile
- `PUT /profile` - Update profile
- `DELETE /profile` - Delete account

## Development

### Running Tests
```bash
# Run all tests
composer test

# Run specific test file
php artisan test tests/Feature/ClassroomTest.php

# Run with coverage
php artisan test --coverage
```

### Code Style
Run StyleCI checks:
```bash
./vendor/bin/php-cs-fixer fix
```

### Database Seeding
```bash
# Run seeders
php artisan db:seed

# Reset and seed
php artisan migrate:fresh --seed
```

## Future Enhancements

- [ ] Export student grades to PDF
- [ ] Bulk student import (CSV)
- [ ] Grade scaling/weighting options
- [ ] Student attendance tracking
- [ ] Grade distribution analytics
- [ ] Multiple instructor support
- [ ] Customizable slideshow themes
- [ ] Email grade reports to students

## License

This project is open source and available under the [MIT License](LICENSE).

## Support

For issues or questions, please open an issue on the project repository.

---

**_Built with ❤️ for instructors and students_**
