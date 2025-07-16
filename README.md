# Task Management System

## 🛠️ Setup & Run Instructions

### 1. Clone the Repository
```bash
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
```

### 2. Install Dependencies
Make sure you have **PHP**, **Composer**, and **MySQL** installed.

```bash
composer install
```

### 3. Environment Configuration
Copy the example environment file and generate an app key:
```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your local DB credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 4. Run Migrations and Seeders
```bash
php artisan migrate --seed
```

### 5. Serve the Application
```bash
php artisan serve
```

The application will be available at:  
📍 `http://localhost:8000`

---

### 🧪 Running Tests
To run the feature and unit tests:

```bash
php artisan test
# or if using Pest
vendor/bin/pest
```

---

### 🔐 Authentication
- The project uses **Laravel Sanctum** for authentication.
- After registering or logging in, include the token in headers:
  ```
  Authorization: Bearer YOUR_TOKEN_HERE
  ```
