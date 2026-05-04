# Hostel Management System

A web application for managing hostel students. Built with **PHP**, **MySQL**, **HTML5**, **CSS3**, and **JavaScript**.

---

## 📋 What You Get

- Admin login & registration
- Add, view, update, delete student records
- Modern responsive design
- Secure database operations
- Form validation

---

## 🚀 Quick Start (4 Steps)

### 1. Create Database
```bash
mysql -u root -p < database/schema.sql
```
(When prompted for password, enter: `database145`)

### 2. Start Server
```bash
cd /Users/harsh/Documents/myfiles/sem_6/WP/Project
php -S localhost:8000
```

### 3. Open in Browser
```
http://localhost:8000/
```

### 4. Register & Login
- Click **"Register here"**
- Create your admin account
- Login with your credentials
- Start managing students!

---

## 📁 Project Files

```
Project/
├── index.php                    ← Login page
├── assets/
│   ├── css/style.css           ← Styling
│   └── js/validation.js        ← Form validation
├── includes/
│   └── config.php              ← Database settings
├── pages/                       ← All page handlers
│   ├── login.php
│   ├── register.php
│   ├── dashboard.php
│   ├── add-student.php
│   ├── view-students.php
│   ├── update-semester.php
│   ├── delete-student.php
│   └── logout.php
└── database/
    └── schema.sql              ← Database schema
```

---

## ⚙️ Configuration

Edit `includes/config.php` if your MySQL credentials are different:

```php
$host = 'localhost';
$db_user = 'root';
$db_password = 'database145';   // Change if needed
$db_name = 'Profexphostelmanagement';
```

---

## 📖 How to Use

1. **Register**: Click "Register here" to create admin account
2. **Login**: Enter username and password
3. **Dashboard**: See 4 main options:
   - ➕ Add Student
   - 📋 View Students
   - 📚 Update Semester
   - 🗑️ Delete Student
4. **Manage**: Fill forms and manage student records
5. **Logout**: Click logout when done

---

## 🐛 Troubleshooting

| Problem | Solution |
|---------|----------|
| Connection error | Check MySQL is running. Verify `includes/config.php` |
| Table not found | Run: `mysql -u root -p < database/schema.sql` |
| Port 8000 in use | Use: `php -S localhost:8001` |
| Permission error | Run: `chmod 755 /path/to/Project` |

---

## ✅ Requirements

- PHP 7.0+
- MySQL 5.7+
- Modern web browser

---

## 📊 Database

The project uses 2 tables:

**admins** - For admin login/registration  
**students** - For student records

---

**Version**: 2.0 | **Status**: Ready to Use ✓
